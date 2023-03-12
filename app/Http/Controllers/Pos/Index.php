<?php

namespace App\Http\Controllers\Pos;

use App\Models\Sales;
use Livewire\Component;
use App\Models\Products;
use App\Models\sale_details;
use Illuminate\Support\Facades\Auth;


class Index extends Component
{
    public  $debt = false,
        $name, $phone, $currentpaid, $discount, $data;
    protected $listeners = ['refresh' => 'render'],
        $queryString = ['debt' => ['except' => false]];
    public function render()
    {
        $sales = Sales::where('invoice',  session('invoice'))->with('sale_details', function ($query) {
            $query->orderByDesc('id')->product();
        })->first();
        return view('pos.index', compact('sales'));
    }
    public function debt()
    {
        $this->debt = !$this->debt;
    }
    public function updatedData()
    {
        $invoice = session('invoice');
        $this->validate(
            ['data' => 'required'],
            [
                'data.required' => __('validation.required', ['attribute' => __('header.data')]),
            ]
        );
        $this->data = trim($this->data);
        $product = Products::where('barcode', $this->data)->orWhere('name', $this->data)->first();
        if (!$product) {
            $this->addError('data', __('validation.exists', ['attribute' => __('header.data')]));
            return;
        }
        // dd($product);

        if ($product->quantity == 0 || $product->expiry_date <= now()) {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            $product->quantity == 0 ?  flash()->addError(__('header.out_of_stock')) : flash()->addError(__('header.expired'));
            return;
        }
        $sales = Sales::where('invoice', $invoice)->first();
        if ($sales == null) {
            $sales = Sales::create([
                'invoice' => $invoice,
                'total' => $product->sale_price
            ]);
            $sales->sale_details()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
            $product->update([
                'quantity' => $product->quantity - 1
            ]);
        } else {
            $sales->total = $sales->total + $product->sale_price;
            $checkProduct_id =  $sales->sale_details()->where('product_id', $product->id)->first();
            if ($checkProduct_id) {
                $checkProduct_id->update([
                    'quantity' => $checkProduct_id->quantity + 1,
                ]);
            } else {
                $sales->sale_details()->create([
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
            $sales->save();
            $product->update([
                'quantity' => $product->quantity - 1
            ]);
        }
        $this->reset('data');
        $this->resetValidation();
        // $this->emit('refresh');
    }

    public function plus(sale_details $sale_details, Products $product, Sales $sales)
    {
        if ($product->quantity > 0) {
            $sale_details->quantity = $sale_details->quantity + 1;
            $sale_details->save();
            $product->quantity = $product->quantity - 1;
            $product->save();
            $sales->total = $sales->total + $product->sale_price;
            $sales->save();
            $this->dispatchBrowserEvent('play', ['sound' => 'beep']);
            flash()->addSuccess(__('header.add'));
        } else {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            flash()->addError(__('header.out_of_stock'));
        }
        $this->reset();
        $this->render();
    }
    public function minus(sale_details $sale_details, Products $product, Sales $sales)
    {
        $sales->total = $sales->total - $product->sale_price;
        $sales->total < 0 ?  0 : $sales->total;
        $sales->save();
        $sale_details->quantity = $sale_details->quantity - 1;
        $sale_details->save();
        $product->quantity = $product->quantity + 1;
        $product->save();
        if ($sale_details->quantity == 0) {
            $sale_details->delete();
            flash()->addWarning(__('header.deleted'));
        }
        $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
        $this->reset();
    }

    public function destroy(sale_details $sale_details, Products $product, Sales $sales)
    {
        if (!$sale_details && !$product && !$sales) {
            return;
        }
        $sales->total = $sales->total - ($product->sale_price * $sale_details->quantity);
        $sales->total < 0 ?  0 : $sales->total;
        $sales->save();
        $product->quantity = $product->quantity + $sale_details->quantity;
        $product->save();
        $sale_details->delete();
        $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
        flash()->addWarning(__('header.deleted'));
        $this->reset();
    }

    public function submit()
    {
        if ($this->debt) {
            $this->validate([
                'name' => 'required|string',
                'phone' => 'required|numeric|digits:11',
                'currentpaid' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
            ], [
                'name.required' => __('validation.required', ['attribute' => __('header.name')]),
                'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
                'phone.numeric' => __('validation.numeric', ['attribute' => __('header.phone')]),
                'phone.digits' => __('validation.digits', ['attribute' => __('header.phone')]),
                'currentpaid.numeric' => __('validation.numeric', ['attribute' => __('header.currentpaid')]),
                'currentpaid.min' => __('validation.min', ['attribute' => __('header.currentpaid')]),
                'discount.numeric' => __('validation.numeric', ['attribute' => __('header.discount')]),
                'discount.min' => __('validation.min', ['attribute' => __('header.discount')]),
            ]);
        }
        $sales = Sales::where('invoice',  session('invoice'))->first();
        if ($sales == null || $sales->total == 0) {
            return;
        }
        $this->discount = $this->discount ?? 0;
        $total = $sales->total - $this->discount;
        $sales->update([
            'user_id' => Auth::user()->id,
            'status' => 1,
            'total' => $total,
            'discount' => $this->discount,
            'paid' => $this->debt ? 0 : 1,
        ]);
        if ($this->debt) {
            $sales->debt_sale()->create([
                'name' => $this->name,
                'phone' => $this->phone,
                'amount' => $sales->total,
                'paid' => $this->currentpaid ?? 0,
                'remain' => $sales->total - $this->currentpaid,
            ]);
        }

        $data = [
            'invoice : ' . $sales->invoice,
            'total Price : ' . (number_format($sales->total, 0, null, '.')),
            'discount : '  . (number_format($sales->discount, 0, null, '.')),
            'debt : ' . ($this->debt ? 'yes' : 'no'),
            'name : ' . ($this->name ?? 'no name'),
            'phone : ' . ($this->phone ?? 'no number'),
            'currentpaid : ' . ($this->currentpaid ? number_format($this->currentpaid, 0, null, '.') : 'no paid'),
        ];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Sale", 'Sale', 'nothing to show', $data);

        flash()->addSuccess(__('header.successSale'));
        session()->forget('invoice');
        return redirect()->route('sales', app()->getLocale());
    }
}
