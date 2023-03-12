<?php

namespace App\Http\Controllers\ProductQuantity;

use App\Models\ProductQuantity;
use App\Models\Products;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Index extends Component
{
    public $product,  $product_id, $product_quantity_id, $UpdateProduct = false, $expired, $purches_price, $sale_price, $quantity, $expire_date;
    public function mount($id)
    {
        // dd($lang,$id);
        if (!Gate::allows('Update Product')) {
            abort(404);
        }
        $this->product_id = $id;
    }

    public function render()
    {
        $this->product = Products::with('ProductsQuantity')->categorys()->suppliers()->ProductQuantity()->findOrFail($this->product_id);
        return view('product-quantity.index');
    }
    public function done()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->UpdateProduct = false;
        $this->dispatchBrowserEvent('closeModal');
    }
    public function add()
    {
        $this->UpdateProduct = false;
    }
    public function updateProduct($id)
    {
        $this->UpdateProduct = true;
        $ProductQuantity = ProductQuantity::findOrFail($id);
        $this->product_quantity_id = $ProductQuantity->id;
        $this->purches_price = $ProductQuantity->purches_price;
        $this->sale_price = $ProductQuantity->sale_price;
        $this->quantity = $ProductQuantity->quantity;
        $this->expire_date = $ProductQuantity->expiry_date;
        $ProductQuantity->expiry_date <= now() ? $this->expired = true : $this->expired = false;
    }
    public function GetRuls()
    {
        return [
            'purches_price' => 'required|numeric|min:3',
            'sale_price' => 'required|numeric|min:3',
            'quantity' => 'required|numeric|min:1',
            'expire_date' => 'required|date|after:today',
        ];
    }
    public function GetMessage()
    {
        return [
            'purches_price.required' => __('validation.required', ['attribute' => __('header.purches_price')]),
            'purches_price.min' => __('validation.min', ['attribute' => __('header.purches_price'), 'min' => 3]),
            'purches_price.numeric' => __('validation.numeric', ['attribute' => __('header.purches_price')]),
            'sale_price.required' => __('validation.required', ['attribute' => __('header.sale_price')]),
            'sale_price.min' => __('validation.min', ['attribute' => __('header.sale_price'), 'min' => 3]),
            'sale_price.numeric' => __('validation.numeric', ['attribute' => __('header.sale_price')]),
            'quantity.required' => __('validation.required', ['attribute' => __('header.quantity')]),
            'quantity.numeric' => __('validation.numeric', ['attribute' => __('header.quantity')]),
            'quantity.min' => __('validation.min', ['attribute' => __('header.quantity'), 'min' => 1]),
            'expire_date.required' => __('validation.required', ['attribute' => __('header.expire_date')]),
            'expire_date.date' => __('validation.date', ['attribute' => __('header.expire_date')]),
            'expire_date.after' => __('validation.after', ['attribute' => __('header.expire_date'), 'date' => __('header.today')]),
            'description.string' => __('validation.string', ['attribute' => __('header.description')]),
        ];
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        $ProductQuantity = ProductQuantity::findOrFail($this->product_quantity_id);
        $ProductQuantity->update([
            'purches_price' => $this->purches_price,
            'sale_price' => $this->sale_price,
            'quantity' => $this->quantity,
            'expiry_date' => $this->expire_date,
        ]);
        flash()->addSuccess(__('header.updated'));
        $this->done();
    }
    public function delete(ProductQuantity $ProductQuantity)
    {
        $ProductQuantity->delete();
        flash()->addSuccess(__('header.deleted'));
        $this->done();
    }
}
