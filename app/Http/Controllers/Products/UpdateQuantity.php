<?php

namespace App\Http\Controllers\Products;

use Livewire\Component;
use App\Models\Products;
use App\Models\ProductsQuantity;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;

class UpdateQuantity extends Component
{
    use WithPagination;
    public $product,  $product_id, $product_quantity_id, $UpdateProduct = false, $expired, $purches_price, $sale_price, $quantity, $expire_date;

    public function mount($id)
    {
        if (!Gate::allows('Update Product')) {
            abort(404);
        }
        $this->product_id = $id;
    }

    public function render()
    {
        $this->product = Products::with(['product_quantity' => function ($query) {
            $query->orderBy('expiry_date');
        }])->categorys()->suppliers()->TotalQuantity()->SalePrice()->findOrFail($this->product_id);
        return view('products.update.index');
    }

    public function done($action = true)
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->reset(['purches_price', 'sale_price', 'quantity', 'expire_date', 'UpdateProduct']);
        $this->resetValidation();
        if ($action) {
            $this->resetErrorBag();
            $this->UpdateProduct = false;
            $this->mount($this->product_id);
        }
    }
    public function add()
    {
        $this->UpdateProduct = false;
    }
    public function updateProduct($id)
    {
        $this->UpdateProduct = true;
        $ProductQuantity = ProductsQuantity::findOrFail($id);
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
            'purches_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'expire_date' => 'date|after:today',
        ];
    }
    public function GetMessage()
    {
        return [
            'purches_price.required' => __('validation.required', ['attribute' => __('header.purches_price')]),
            'purches_price.min' => __('validation.min', ['attribute' => __('header.purches_price'), 'min' => 0]),
            'purches_price.numeric' => __('validation.numeric', ['attribute' => __('header.purches_price')]),
            'sale_price.required' => __('validation.required', ['attribute' => __('header.sale_price')]),
            'sale_price.min' => __('validation.min', ['attribute' => __('header.sale_price'), 'min' => 0]),
            'sale_price.numeric' => __('validation.numeric', ['attribute' => __('header.sale_price')]),
            'quantity.required' => __('validation.required', ['attribute' => __('header.quantity')]),
            'quantity.numeric' => __('validation.numeric', ['attribute' => __('header.quantity')]),
            'quantity.min' => __('validation.min', ['attribute' => __('header.quantity'), 'min' => 1]),
            'expire_date.date' => __('validation.date', ['attribute' => __('header.expire_date')]),
            'expire_date.after' => __('validation.after', ['attribute' => __('header.expire_date'), 'date' => __('header.today')]),
            'description.string' => __('validation.string', ['attribute' => __('header.description')]),
        ];
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        if ($this->UpdateProduct) {
            $ProductQuantity = ProductsQuantity::findOrFail($this->product_quantity_id);
            $ProductQuantity->update([
                'purches_price' => $this->purches_price,
                'sale_price' => $this->sale_price,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expire_date ?? $ProductQuantity->expiry_date,
            ]);
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.updated')]);
        } else {
            ProductsQuantity::create([
                'product_id' => $this->product_id,
                'purches_price' => $this->purches_price,
                'sale_price' => $this->sale_price,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expire_date,
            ]);
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.add')]);
        }
        $product =  $this->product->SalePrice()->findOrFail($this->product_id);
        $product->update([
            'sale_price' => $product->final_sale_price,
        ]);
        $this->done();
    }
    public function delete(ProductsQuantity $ProductQuantity)
    {
        if (!Gate::allows('Delete Product')) {
            abort(404);
        }

        $ProductQuantity->delete();
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.deleted')]);
        $this->done();
    }
}
