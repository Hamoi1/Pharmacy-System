<?php

namespace App\Http\Controllers\Pos;

use App\Models\Sales;
use Livewire\Component;
use App\Models\Products;
use App\Models\Customers;
use App\Models\ProductsQuantity;
use App\Models\sale_details;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Index extends Component
{
    public  $debt = false, $name, $phone, $email, $address, $search, $currentpaid, $discount, $data, $product, $sales, $supplier, $customer, $guarantorphone, $guarantoraddress, $SupplierSearch, $CustomerSearch, $invoice, $invoices = [], $SaleTypeView;
    protected $queryString = ['debt' => ['except' => false], 'supplier' => ['except' => ''], 'customer' => ['except' => ''], 'invoice' => ['except' => ''], 'SaleTypeView' => ['except' => '']],
        $listeners = ['RefreshCustomer' => '$refresh', 'RefreshSupplier' => '$refresh', 'dataCheck' => 'updatedData'];
    public function getInvoice()
    {
        $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
        $number = Str::limit($number, 9, '');
        // check if invoice
        $check = Sales::where('invoice', $number)->first();
        if ($check) {
            $this->getInvoice();
        }
        return Str::start($number, 'inv-');
    }
    public function render()
    {
        $this->invoices = DB::table('sales')->select('invoice')->where('status', 0)->orderByDesc('id')->get()->toArray();
        if ($this->invoices == null) {
            $this->AddNewInvoce();
        }
        if ($this->invoices != null) {
            $this->invoice =   $this->invoice == null ? $this->invoices[0]->invoice :  $this->invoice;
        }
        $this->SaleTypeView = $this->SaleTypeView == null ? 'ListView' : $this->SaleTypeView;
        $this->sales = Sales::where('invoice', $this->invoice)->where('status', 0)->with('sale_details', function ($query) {
            $query->with(['ProductQuantity'])->orderByDesc('id')->product();
        })->first();
        // dd($this->sales);
        $this->SupplierSearch != null ?
            $suppliers = DB::table('suppliers')->select(['id', 'name'])->where(function ($query) {
                $query->where('name', 'like', '%' . $this->SupplierSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->SupplierSearch . '%');
            })->orderByDesc('id')->get() :
            $suppliers = DB::table('suppliers')->select(['id', 'name'])->orderByDesc('id')->get();
        $this->CustomerSearch != null ?
            $customers = DB::table('customers')->select(['id', 'name'])->where(function ($query) {
                $query->where('name', 'like', '%' . $this->CustomerSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->CustomerSearch . '%');
            })->orderByDesc('id')->get() :
            $customers = DB::table('customers')->select(['id', 'name'])->orderByDesc('id')->get();
        $back = url()->previous();
        $products = DB::table('products')->select(['id', 'name', 'barcode', 'image'])->orderByDesc('id')->get();
        return view('pos.index', compact('suppliers', 'customers', 'back', 'products'));
    }
    public function AddNewInvoce()
    {
        $invoice = $this->getInvoice();
        Sales::create([
            'invoice' => $invoice,
            'total' => 0,
        ]);
        $this->invoices = $this->invoices;
    }
    public function debt()
    {
        $this->debt = !$this->debt;
    }
    public function updatedData()
    {
        if (is_numeric($this->data)) {
            $this->validate(['data' => 'required|exists:products,barcode'], [
                'data.required' => __('validation.required', ['attribute' => __('header.data')]),
                'data.exists' => __('validation.exists', ['attribute' => __('header.data')])
            ]);
            $product_id = DB::table('products')->select(['id', 'barcode'])->where('barcode', $this->data)->first()->id;
            $this->AddProduct($product_id);
        } else {
            $this->data != '' ? $this->product = DB::table('products')->select(['id', 'name', 'barcode'])->where('name', 'like', '%' . $this->data . '%')->get() : $this->product = null;
        }
    }
    public function AddProduct(int $id)
    {
        $product = Products::with('product_quantity')->SalePrice()->TotalQuantity()->find($id);
        if (!$product) {
            return;
        } else {
            if ($product->total_quantity == 0) {
                $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
                $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.out_of_stock')]);
            } else {
                $product_quantity = $product->product_quantity()->where('quantity', '>', 0)->whereDate('expiry_date', '>', now())->first();
                if ($product_quantity?->quantity  == 0) {
                    $product_quantity = $product->product_quantity()->where('quantity', '>', 0)->where('expiry_date', '!=', $product_quantity?->expiry_date)->whereDate('expiry_date', '>', now())->first();
                }
                if ($product_quantity == null) {
                    $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
                    $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.expired')]);
                } else {

                    $sales = $this->sales;
                    if ($sales == null) {
                        $sales = Sales::create([
                            'invoice' => $this->invoice,
                            'total' => $product->sale_price
                        ]);
                        $sales->sale_details()->create([
                            'product_id' => $product->id,
                            'product_quantity_id' => $product_quantity->id, // 'product_quantity_id
                            'quantity' => 1,
                        ]);
                        $product_quantity->update([
                            'quantity' => $product_quantity->quantity - 1
                        ]);
                    } else {
                        $sales->total = $sales->total + $product->sale_price;
                        $checkProduct_id =  $sales->sale_details
                            ->where('product_id', $product->id)
                            ->where('product_quantity_id', $product_quantity->id) // 'product_quantity_id
                            ->first();
                        if ($checkProduct_id) {
                            $checkProduct_id->update([
                                'quantity' => $checkProduct_id->quantity + 1,
                            ]);
                        } else {
                            $sales->sale_details()->create([
                                'product_id' => $product->id,
                                'product_quantity_id' => $product_quantity->id, // 'product_quantity_id
                                'quantity' => 1,
                            ]);
                        }
                        $sales->saveQuietly();
                        $product_quantity->update([
                            'quantity' => $product_quantity->quantity - 1
                        ]);
                    }
                }
            }
            $this->data = null;
            $this->product = null;
        }
    }
    public function plus(sale_details $sale_details, $id, Sales $sales)
    {
        $product_quantity = ProductsQuantity::where('quantity', '>', 0)->whereDate('expiry_date', '>', now())->with(['products' => function ($query) {
            $query->SalePrice();
        }])->find($id);
        if ($product_quantity == null) {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.expired') . ' ' . __('header.or') . ' ' . __('header.StockOut')]);
            return;
        }
        $sale_details->update([
            'quantity' => $sale_details->quantity + 1
        ]);
        $product_quantity->update([
            'quantity' => $product_quantity->quantity - 1
        ]);
        $sales->update([
            'total' => $sales->total + $product_quantity->products->final_sale_price
        ]);
        $this->dispatchBrowserEvent('play', ['sound' => 'beep']);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.add')]);
    }
    public function minus(sale_details $sale_details, $id, Sales $sales)
    {
        $product_quantity = ProductsQuantity::with(['products' => function ($query) {
            $query->SalePrice();
        }])->find($id);
        if ($product_quantity == null) {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NoData')]);
            return;
        }
        if ($sale_details->quantity == 1) {
            $sale_details->delete();
        } else {
            $sale_details->update([
                'quantity' => $sale_details->quantity - 1
            ]);
        }
        $product_quantity->update([
            'quantity' => $product_quantity->quantity + 1
        ]);
        $total = $sales->total - $product_quantity->products->final_sale_price < 0 ? 0 : $sales->total - $product_quantity->products->final_sale_price;
        $sales->update([
            'total' => $total
        ]);
        $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.deleted')]);
    }
    public function destroy(sale_details $sale_details, $id, Sales $sales, $totalPrice)
    {
        if ($id != null) {
            $product_quantity = ProductsQuantity::with(['products' => function ($query) {
                $query->select(['id'])->SalePrice();
            }])->find($id);
            if ($product_quantity == null) {
                flash()->addError(__('header.NoData'));
                return;
            }
        }
        if ($id == null) {
            $pirce = $totalPrice;
        } else {
            $pirce = $product_quantity->products->final_sale_price * $sale_details->quantity;
        }
        $sales->update([
            'total' => $sales->total - $pirce
        ]);
        $sales->total = $sales->total < 0 ?  0 : $sales->total;
        $sales->saveQuietly();
        if ($id != null) {
            $product_quantity->update([
                'quantity' => $product_quantity->quantity + $sale_details->quantity
            ]);
        }
        $sale_details->delete();
        $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
        $this->dispatchBrowserEvent('message', ['type' => 'warning', 'message' => __('header.deleted')]);
    }
    public function submit($check)
    {
        $this->validate([
            'discount' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|exists:suppliers,id',
            'customer' => 'required|exists:customers,id',
        ], [
            'discount.numeric' => __('validation.numeric', ['attribute' => __('header.discount')]),
            'discount.min' => __('validation.min', ['attribute' => __('header.discount')]),
            'supplier.exists' => __('validation.exists', ['attribute' => __('header.supplier')]),
            'customer.required' => __('validation.required', ['attribute' => __('header.Customer')]),
            'customer.exists' => __('validation.exists', ['attribute' => __('header.Customer')]),
        ]);
        if ($this->debt) {
            $this->validate([
                'currentpaid' => 'required|numeric|min:0',
            ], [
                'currentpaid.required' => __('validation.required', ['attribute' => __('header.currentpaid')]),
                'currentpaid.numeric' => __('validation.numeric', ['attribute' => __('header.currentpaid')]),
                'currentpaid.min' => __('validation.min', ['attribute' => __('header.currentpaid')]),
            ]);
        }
        $sale = Sales::where('invoice',  $this->invoice)->first();
        if ($sale == null || $sale->total == 0) {
            return;
        }
        $this->discount = $this->discount ?? 0;
        if ($this->discount > 0 && $this->discount <= 100) {
            $total = $sale->total - ($sale->total * $this->discount / 100);
        } else {
            $total = $sale->total - $this->discount;
        }
        $sale->update([
            'user_id' => auth()->user()->id,
            'customer_id' => $this->customer,
            'supplier_id' => $this->supplier,
            'status' => 1,
            'total' => $total,
            'discount' => $this->discount,
            'paid' => $this->debt ? 0 : 1,
        ]);
        if ($this->debt) {
            $sale->debt_sale()->create([
                'customer_id' => $this->customer,
                'amount' => $sale->total,
                'paid' => $this->currentpaid ?? 0,
                'remain' => $sale->total - $this->currentpaid,
            ]);
        }
        $this->customer ? $customer = Customers::select(['name', 'phone'])->find($this->customer) : $customer = null;
        $data = [
            'invoice : ' . $sale->invoice,
            'total Price : ' . (number_format($sale->total, 0)),
            'discount : '  . (number_format($sale->discount, 0)),
            'debt : ' . ($this->debt ? 'yes' : 'no'),
            'name : ' . ($customer?->name),
            'phone : ' . ($customer?->phone),
            'currentpaid : ' . ($this->currentpaid ? number_format($this->currentpaid, 2) : 'no paid'),
        ];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Sale", 'Sale', '["nothing to show"]', $data);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.successSale')]);
        $this->AddNewInvoce();
        $this->resetValidation();
    }
    public function salePrint()
    {
        $this->submit(false);
        $route = route('sales.print', ['lang' => app()->getLocale(), 'id' => $this->sales->id]);
        $this->dispatchBrowserEvent('print', ['route' => $route]);
    }
}
