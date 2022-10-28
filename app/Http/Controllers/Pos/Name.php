<?php

namespace App\Http\Controllers\Pos;

use App\Models\Sales;
use Livewire\Component;
use App\Models\Products;

class Name extends Component
{
    public $name;
    public function render()
    {
        return view('pos.name');
    }

    public function add()
    {
        $invoice = session('invoice');
        $this->validate(
            ['name' => 'required|string|exists:products,name'],
            [
                'name.required' => __('validation.required', ['attribute' => __('header.product_name')]),
                'name.string' => __('validation.string', ['attribute' => __('header.product_name')]),
                'name.exists' => __('validation.exists', ['attribute' => __('header.product_name')]),
            ]
        );
        $product = Products::where('name', $this->name)->first();
        if ($product->quantity == 0 || $product->expiry_date <= now()) {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            $product->quantity == 0 ?  flash()->addError('header.out_of_stock') : flash()->addError('header.expired');
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
            $sales->save();
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
            $product->update([
                'quantity' => $product->quantity - 1
            ]);
        }
        $this->reset('name');
        $this->resetValidation();
        $this->dispatchBrowserEvent('play', ['sound' => 'beep']);
        $this->emit('refresh');
    }
}
