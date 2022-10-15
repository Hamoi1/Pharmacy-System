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
        $name, $phone, $currentpaid, $discount;
    protected $listeners = ['refresh' => 'render'],
        $queryString = ['debt' => ['except' => false]];
    public function render()
    {
        $sales = Sales::where('invoice',  session('invoice'))->with('sale_details', function ($query) {
            $query->product();
        })->first();
        return view('pos.index', compact('sales'));
    }
    public function debt()
    {
        $this->debt = !$this->debt;
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
            notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.add'));
        } else {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addError(__('header.out_of_stock'));
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
            notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addWarning(__('header.deleted'));
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
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addWarning(__('header.deleted'));
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
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.successSale'));
        session()->forget('invoice');
        return redirect()->route('sales', app()->getLocale());
    }
}
