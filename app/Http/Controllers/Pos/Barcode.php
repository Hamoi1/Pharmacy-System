<?php

namespace App\Http\Controllers\Pos;

use App\Models\Sales;
use Livewire\Component;
use App\Models\Products;

class Barcode extends Component
{
    public $barcode;
    public function render()
    {
        return view('pos.barcode');
    }
    public function updatedBarcode()
    {
        $invoice = session('invoice');
        $this->validate(
            ['barcode' => 'required|exists:products,barcode'],
            [
                'barcode.required' => __('validation.required', ['attribute' => __('header.barcode')]),
                'barcode.exists' => __('validation.exists', ['attribute' => __('header.barcode')]),
            ]
        );
        $product = Products::where('barcode', $this->barcode)->first();
        if ($product->quantity == 0 || $product->expiry_date <= now()) {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            $product->quantity == 0 ?  notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addError(__('header.out_of_stock')) : notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addError(__('header.expired'));
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
        $this->reset('barcode');
        $this->resetValidation();
        $this->emit('refresh');
    }
}
