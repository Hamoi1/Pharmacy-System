<?php

namespace App\Http\Controllers\Products;

use Livewire\Component;
use App\Models\Products;
use App\Models\Categorys;
use App\Models\Suppliers;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ExportController;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $barcode, $purches_price, $sale_price, $category_id, $supplier_id, $description, $quantity, $expire_date, $images = [], $search, $productID, $ExpiryOrStockedOut,
        $UpdateProduct = false, $product, $product_quantity = [], $Category, $Supplier, $expire = false, $Trashed = false;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['as' => 's', 'except' => ''], 'Category' => ['as' => 'category', 'except' => ''], 'Supplier' => ['as' => 'supplier', 'except' => ''],
        'ExpiryOrStockedOut' => ['as' => 'status'],
        'Trashed' => ['except' => false],
    ];
    public $ExportData = [
        'name' => "Name",
        'barcode' => 'Barcode',
        'purches_price' => 'Purches Price',
        'sale_price' => 'Slae Price',
        'category_id' => 'Category',
        'supplier_id' => "Supplier",
        'quantity' => 'Quantity',
        'expiry_date' => 'Expiry Date',
        'description' => 'Description',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',

    ], $ExportDataSelected = [], $Productquantity;
    public function mount()
    {
        if (!Gate::allows('View Product')) {
            abort(404);
        }
        Gate::allows('Product Trash') ? $this->Trashed  : $this->Trashed = false;
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
            'products' => $products->orderByDesc('id')->SalePrice()->ExpiryDate()->TotalQuantity()->suppliers()->categorys()->paginate(10),
            'categorys' => Categorys::select(['id', 'name'])->get()->toArray(),
            'suppliers' => Suppliers::select(['id', 'name'])->get()->toArray(),
            'GetTrashDate' => $GetTrashDate,
        ]);
    }
    public function ResetData()
    {
        return [
            'name', 'barcode', 'purches_price', 'sale_price', 'category_id', 'supplier_id', 'quantity', 'expire_date', 'images', 'UpdateProduct', 'productID', 'description', 'expire', 'ExportDataSelected', 'Productquantity'
        ];
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
            'name' => 'required|string|min:2|max:255|regex:/^[a-zA-Z0-9 \s]+$/|unique:products,name,' . $this->productID ?? '',
            'barcode' => 'required|min:3|numeric|unique:products,barcode,' . $this->productID ?? '',
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
            'name.unique' => __('validation.unique', ['attribute' => __('header.product_name')]),
            'name.regex' => __('validation.regex', ['attribute' => __('header.product_name')]),
            'barcode.required' => __('validation.required', ['attribute' => __('header.barcode')]),
            'barcode.min' => __('validation.min', ['attribute' => __('header.barcode'), 'min' => 3]),
            'barcode.numeric' => __('validation.numeric', ['attribute' => __('header.barcode')]),
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
    public function updated($propertyName)
    {
        $this->resetPage();
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        $images = [];
        if ($this->images) {
            foreach ($this->images as $image) {
                $imageName = time() . '-' . uniqid() . '-' . uniqid() . '.' . $image->GetClientOriginalExtension();
                Storage::put('public/products/' . $imageName);
                $images[] = $imageName;
            }
        }
        if ($this->UpdateProduct && Gate::allows('Update Product')) {
            $product =  Products::findOrFail($this->productID);
            $oldData = [
                'Name : ' . $product->name,
                'barcode : ' . $product->barcode,
                'Quantity : ' . $product->quantity,
                'expiry Date : ' . $product->expiry_date,
                'Purches Price : ' . $product->purches_price,
                'Sales Price : ' . $product->sale_price,
                'Catrgory : ' . $product->category->name,
                'Supplier : ' . $product->supplier->name,
            ];
            $product->update([
                'name' => $this->name,
                'barcode' => $this->barcode,
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id,
                'description' => $this->description,
            ]);
            $newData = [
                'Name : ' . $product->name,
                'barcode : ' . $product->barcode,
                'Catrgory : ' . $product->category->name,
                'Supplier : ' . $product->supplier->name,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Product", 'Update',  $oldData,  $newData);
        } else if (!$this->UpdateProduct  && Gate::allows('Insert Product')) {
            $product = Products::create([
                'name' => $this->name,
                'barcode' => $this->barcode,
                'quantity' => $this->quantity,
                'sale_price' => $this->sale_price,
                'purches_price' => $this->purches_price,
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id,
                'description' => $this->description,
                'image' => $images ? json_encode($images) : null,
                'user_id' => auth()->id(),
            ]);
            $product->product_quantity()->create([
                'quantity' => $this->quantity,
                'purches_price' => $this->purches_price,
                'sale_price' => $this->sale_price,
                'expiry_date' => $this->expire_date,
            ]);
            $newData = [
                'Name : ' . $this->name,
                'barcode : ' . $this->barcode,
                'Quantity : ' . $this->quantity,
                'expiry Date : ' . $this->expire_date,
                'Purches Price : ' . $this->purches_price,
                'Sales Price : ' . $this->sale_price,
                'Catrgory : ' . $product->category->name,
                'Supplier : ' . $product->supplier->name,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Product", 'Create',  'nothing to show',  $newData);
        }
        flash()->addSuccess(__('header.Product') . ' ' . $this->UpdateProduct ? __('header.updated') : __('header.add'));

        $this->done();
    }

    public function updateProduct($id)
    {
        if (!Gate::allows('Update Product')) {
            flash()->adderror(__('header.NotAllowToDo'));
        } else {
            $product = Products::findOrFail($id);
            $this->product = $product;
            $this->UpdateProduct = true;
            $this->productID = $product->id;
            $this->name = $product->name;
            $this->barcode = $product->barcode;
            $this->category_id = $product->category_id;
            $this->supplier_id = $product->supplier_id;
            $this->description = $product->description;
            $this->purches_price = $product->purches_price;
            $this->sale_price = $product->sale_price;
            $this->quantity = $product->quantity;
            $this->expire_date = $product->expiry_date;
            $product->expiry_date <= now() ? $this->expire = true : $this->expire = false;
        }
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }
    public function delete(Products $product)
    {
        if (!Gate::allows('Delete Product')) {
            flash()->adderror(__('header.NotAllowToDo'));
        } else {
            $data = 'Delete ( ' . $product->name . ' ) form : ' . now();
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Product", 'Delete',  $data,  $data);
            $product->delete();
            flash()->addSuccess(__('header.deleted_for_30_days'));
        }
        $this->done();
    }
    public function show($id)
    {
        $this->product  = Products::with('product_quantity')->TotalQuantity()->SalePrice()->suppliers()->categorys()->findOrFail($id);
    }
    public function DeleteAll()
    {
        $products = $this->CheckTrashParameter()->with('sale_details')->get();
        $ProductName = [];
        foreach ($products as $product) {
            foreach ($product->sale_details as $p) {
                $p->update([
                    'product_id' => null,
                ]);
            }
            $ProductName[] = '( ' . $product->name . ' )';
            $product->forceDelete();
        }
        $data = 'Delete  ' . implode(' , ', $ProductName) . '  form : ' . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Product", 'Delete',  $data,  $data);
        flash()->addSuccess(__('header.Product') . ' ' . __('header.deleted'));
        $this->done();
    }
    public function RestoreAll()
    {
        $products = $this->CheckTrashParameter()->get();
        $ProductName = [];
        foreach ($products as $product) {
            $ProductName[] = '( ' . $product->name . ' )';
            $product->restore();
        }
        $data = 'Restore  ' . implode(' , ', $ProductName) . '  form : ' . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Product", 'Restore',  $data, 'nothing to show');
        flash()->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
    public function restore($id)
    {
        $product = Products::onlyTrashed()->findOrFail($id);
        $ProductName = $product->name;
        $product->restore();
        $data = 'Restore ( ' . $ProductName . ' ) form : ' . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Product", 'Restore',  $data,  'nothing to show');
        flash()->addSuccess(__('header.Product') . ' ' . __('header.RestoreMessage'));
        $this->done();
    }
    public function Upload($data)
    {
        if (!in_array($data, $this->ExportDataSelected)) {
            $this->ExportDataSelected[] = $data;
        } else {
            $this->ExportDataSelected = array_diff($this->ExportDataSelected, [$data]);
        }
    }

    public function ExportData()
    {
        $this->validate(
            [
                'Productquantity' => 'nullable|numeric',
            ]
        );

        if (count($this->ExportDataSelected) == 0) {
            flash()->addError(__('header.SelectData'));
            return;
        }
        $data = '';
        foreach ($this->ExportDataSelected as $key => $value) {
            $data .= $value . ' , ';
        }
        $data = 'Export  ' . $data . '  form : ' . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "User", 'Export',  $data,  $data);
        $this->ExportDataSelected = array_unique($this->ExportDataSelected);
        return  ExportController::export($this->ExportDataSelected, 'products', $this->Productquantity);
    }
}
