<?php

namespace App\Http\Controllers\Products;

use App\Jobs\AddProdut;
use Livewire\Component;
use App\Models\Products;
use App\Models\Categorys;
use App\Models\Suppliers;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $barcode, $purches_price, $sale_price, $category_id, $supplier_id, $description, $quantity, $expire_date, $images = [], $search, $productID, $ExpiryOrStockedOut;
    public $UpdateProduct = false, $product, $Category, $Supplier, $expire = false, $Trashed = false;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['as' => 's', 'except' => ''], 'Category' => ['as' => 'category', 'except' => ''], 'Supplier' => ['as' => 'supplier', 'except' => ''],
        'ExpiryOrStockedOut' => ['as' => 'status'],
        'Trashed' => ['except' => false],
    ];
    public function mount()
    {
        Gate::allows('admin') ? $this->Trashed  : $this->Trashed = false;
    }
    private function CheckTrashParameter()
    {
        if ($this->Trashed) {
            return Products::onlyTrashed();
        } else {
            return Products::query();
        }
    }
    public function Trash()
    {
        $this->Trashed = !$this->Trashed;
        $this->resetPage();
    }
    public function render()
    {
        $products =  $this->CheckTrashParameter();
        $this->search ?
            $products->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%');
            }) : '';
        $this->Category ? $products->where('category_id', $this->Category) : '';
        $this->Supplier ? $products->where('supplier_id', $this->Supplier) : '';

        if ($this->ExpiryOrStockedOut === 'e') {
            $products->where('expiry_date', '<=', now());
        } elseif ($this->ExpiryOrStockedOut === 's') {
            $products->where('quantity', '=', 0);
        }
        $GetTrashDate = function ($date) {
            return $date->addMonth()->format('Y-m-d');
        };
        return view('products.index', [
            'products' => $products->latest()->suppliers()->categorys()->paginate(10),
            'categorys' => Categorys::all(),
            'suppliers' => Suppliers::all(),
            'GetTrashDate' => $GetTrashDate,
        ]);
    }
    public function ResetData()
    {
        return ['name', 'barcode', 'purches_price', 'sale_price', 'category_id', 'supplier_id', 'quantity', 'expire_date', 'images', 'UpdateProduct', 'productID', 'description', 'expire'];
    }
    public function add()
    {
        $this->UpdateProduct = false;
        $this->expire = false;
    }
    public function done()
    {
        $this->reset($this->ResetData());
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function GetRuls()
    {
        return [
            'name' => 'required|string|min:2|max:255|regex:/^[a-zA-Z0-9 \s]+$/',
            'barcode' => 'required|min:3|max:100|unique:products,barcode,' . $this->productID ?? '',
            'purches_price' => 'required|numeric|min:3',
            'sale_price' => 'required|numeric|min:3',
            'category_id' => 'nullable|numeric|exists:categorys,id',
            'supplier_id' => 'nullable|numeric|exists:suppliers,id',
            'quantity' => 'required|numeric|min:1',
            'expire_date' => 'required|date|after:today',
            'images.*' => 'image|max:20000|mimes:jpg,jpeg,png,svg|nullable',
            'description' => 'nullable|string',
        ];
    }
    public function GetMessage()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('header.product_name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.product_name')]),
            'name.min' => __('validation.min', ['attribute' => __('header.product_name'), 'min' => 2]),
            'name.max' => __('validation.max', ['attribute' => __('header.product_name'), 'max' => 255]),
            'name.regex' => __('validation.regex', ['attribute' => __('header.product_name')]),
            'barcode.required' => __('validation.required', ['attribute' => __('header.barcode')]),
            'barcode.min' => __('validation.min', ['attribute' => __('header.barcode'), 'min' => 3]),
            'barcode.max' => __('validation.max', ['attribute' => __('header.barcode'), 'max' => 100]),
            'barcode.unique' => __('validation.unique', ['attribute' => __('header.barcode')]),
            'purches_price.required' => __('validation.required', ['attribute' => __('header.purches_price')]),
            'purches_price.min' => __('validation.min', ['attribute' => __('header.purches_price'), 'min' => 3]),
            'purches_price.numeric' => __('validation.numeric', ['attribute' => __('header.purches_price')]),
            'sale_price.required' => __('validation.required', ['attribute' => __('header.sale_price')]),
            'sale_price.min' => __('validation.min', ['attribute' => __('header.sale_price'), 'min' => 3]),
            'sale_price.numeric' => __('validation.numeric', ['attribute' => __('header.sale_price')]),
            'category_id.required' => __('validation.required', ['attribute' => __('header.Category')]),
            'category_id.numeric' => __('validation.numeric', ['attribute' => __('header.Category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('header.Category')]),
            'supplier_id.required' => __('validation.required', ['attribute' => __('header.supplier')]),
            'supplier_id.numeric' => __('validation.numeric', ['attribute' => __('header.supplier')]),
            'supplier_id.exists' => __('validation.exists', ['attribute' => __('header.supplier')]),
            'quantity.required' => __('validation.required', ['attribute' => __('header.quantity')]),
            'quantity.numeric' => __('validation.numeric', ['attribute' => __('header.quantity')]),
            'quantity.min' => __('validation.min', ['attribute' => __('header.quantity'), 'min' => 1]),
            'images.*.image' => __('validation.image', ['attribute' => __('header.image')]),
            'images.*.max' => __('validation.max', ['attribute' => __('header.image'), 'max' => 20000]),
            'images.*.mimes' => __('validation.mimes', ['attribute' => __('header.image'), 'values' => 'jpg,jpeg,png,svg']),
            'expire_date.required' => __('validation.required', ['attribute' => __('header.expire_date')]),
            'expire_date.date' => __('validation.date', ['attribute' => __('header.expire_date')]),
            'expire_date.after' => __('validation.after', ['attribute' => __('header.expire_date'), 'date' => __('header.today')]),
            'description.string' => __('validation.string', ['attribute' => __('header.description')]),
        ];
    }
    public function updatedimages()
    {
        $this->validate([
            'images.*' => 'image|max:20000|mimes:jpg,jpeg,png,svg|nullable',
        ], $this->GetMessage());
    }

    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        $images = [];
        if ($this->images) {
            foreach ($this->images as $image) {
                $ResizeImage = Image::make($image)->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg', 75);
                $ResizeImage->stream();
                $imageName = time() . '-' . uniqid() . '-' . uniqid() . '.' . $image->GetClientOriginalExtension();
                Storage::put('public/products/' . $imageName, $ResizeImage);
                $images[] = $imageName;
            }
        }
        if ($this->UpdateProduct) {
            $product = Products::findOrFail($this->productID)->update([
                'name' => $this->name,
                'barcode' => $this->barcode,
                'purches_price' => $this->purches_price,
                'sale_price' => $this->sale_price,
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expire_date,
                'description' => $this->description,
            ]);
        } else {
            $product =  Products::create([
                'name' => $this->name,
                'barcode' => $this->barcode,
                'purches_price' => $this->purches_price,
                'sale_price' => $this->sale_price,
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expire_date,
                'description' => $this->description,
                'image' => $images ? json_encode($images) : null,
            ]);
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess($this->UpdateProduct ? __('header.updated') : __('header.add'));
        $this->done();
    }
    public function updateProduct(Products $product)
    {
        $this->UpdateProduct = true;
        $this->productID = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->purches_price = $product->purches_price;
        $this->sale_price = $product->sale_price;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->quantity = $product->quantity;
        $this->expire_date = $product->expiry_date;
        $this->description = $product->description;
        $product->expiry_date <= now() ? $this->expire = true : $this->expire = false;
    }
    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }
    public function delete(Products $product)
    {
        $product->delete();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.delete'));
        $this->done();
    }
    public function show(Products $product)
    {
        $this->product = $product;
    }
    public function DeleteAll()
    {
        $products = $this->CheckTrashParameter()->get();
        foreach ($products as $product) {
            $product->forceDelete();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function RestoreAll()
    {
        $products = $this->CheckTrashParameter()->get();
        foreach ($products as $product) {
            $product->restore();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
    public function restore($id)
    {
        $product = Products::onlyTrashed()->findOrFail($id)->restore();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
}
