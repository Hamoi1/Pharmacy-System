<?php

namespace App\Http\Controllers\Pos;

use App\Models\Sales;
use Livewire\Component;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Suppliers;
use App\Models\sale_details;
use Illuminate\Support\Facades\Cookie;

class Index extends Component
{
    public  $debt = false,
        $name, $phone, $email, $address, $currentpaid, $discount, $data, $product, $saleID, $supplier, $customer, $guarantorphone, $guarantoraddress, $SupplierSearch, $CustomerSearch;
    protected $queryString = ['debt' => ['except' => false], 'supplier' => ['except' => ''], 'customer' => ['except' => '']],
        $listeners = ['RefreshCustomer' => '$refresh', 'RefreshSupplier' => '$refresh'];
    public function render()
    {
        $sales = Sales::where('invoice',  session('invoice'))->with('sale_details', function ($query) {
            $query->orderByDesc('id')->product();
        })->first();
        $this->SupplierSearch != null ?
            $suppliers = Suppliers::select(['id', 'name'])->where(function ($query) {
                $query->where('name', 'like', '%' . $this->SupplierSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->SupplierSearch . '%');
            })->orderByDesc('id')->get() :
            $suppliers = Suppliers::select(['id', 'name'])->orderByDesc('id')->get();
        $this->CustomerSearch != null ?
            $customers =  Customers::select(['id', 'name',])->where(function ($query) {
                $query->where('name', 'like', '%' . $this->CustomerSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->CustomerSearch . '%');
            })->orderByDesc('id')->get() :
            $customers = Customers::select(['id', 'name',])->orderByDesc('id')->get();
        return view('pos.index', compact('sales', 'suppliers', 'customers'));
    }
    public function done()
    {
        $this->reset(['name', 'email', 'phone', 'address', 'guarantorphone', 'guarantoraddress']);
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }

    public function debt()
    {
        $this->debt = !$this->debt;
    }
    public function updatedData()
    {
        if (is_numeric($this->data)) {
            $this->validate(['data' => 'required'], [
                'data.required' => __('validation.required', ['attribute' => __('header.data')]),
            ]);
        } else {
            $this->data != null && $this->data != "" ?  $this->product = Products::where('name', 'like', '%' . $this->data . '%')->get() : $this->product = null;
        }
        $this->data = trim($this->data);
        $product = Products::with('product_quantity')->TotalQuantity()->where('barcode', $this->data)->orWhere('name', $this->data);
        $checkProduct =   $product->first();
        if (!$checkProduct) {
            return;
        }
        if ($checkProduct->total_quantity == 0) {
            $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
            flash()->addError(__('header.out_of_stock'));
            return;
        }
        $min_expiry_date = $this->checkProductExpiryDate($product);
        $product_quantity = $checkProduct->product_quantity->where('expiry_date', $min_expiry_date)->first();
        if ($product_quantity->quantity  == 0) {
            // get product_quantity wherenot expiry date != min_expiry_date 
            $product_quantity = $checkProduct->product_quantity->where('expiry_date', '!=', $min_expiry_date)->where('quantity', '>', 0);
            //  product_quantity store by expiry_date to low for high
            $product_quantity = $product_quantity->sortBy('expiry_date')->first();
        }
        $this->sessionSet(1, $product_quantity->id, $product_quantity->expiry_date);
        // dd($product_quantity);
        $sales = Sales::where('invoice',  session('invoice'))->with('sale_details', function ($query) {
            $query->orderByDesc('id')->product();
        })->first();
        if ($sales == null) {
            $sales = Sales::create([
                'invoice' => session('invoice'),
                'total' => $checkProduct->sale_price
            ]);
            $sales->sale_details()->create([
                'product_id' => $checkProduct->id,
                'quantity' => 1,
            ]);
            $product_quantity->update([
                'quantity' => $product_quantity->quantity - 1
            ]);
        } else {
            $sales->total = $sales->total + $checkProduct->sale_price;
            $checkProduct_id =  $sales->sale_details()->where('product_id', $checkProduct->id)->first();
            if ($checkProduct_id) {
                $checkProduct_id->update([
                    'quantity' => $checkProduct_id->quantity + 1,
                ]);
            } else {
                $sales->sale_details()->create([
                    'product_id' => $checkProduct->id,
                    'quantity' => 1,
                ]);
            }
            $sales->save();
            $product_quantity->update([
                'quantity' => $product_quantity->quantity - 1
            ]);
        }
        if (is_numeric($this->data)) {
            $this->reset('data');
        }
        $this->resetValidation();
    }
    public function plus(sale_details $sale_details, $product_id, Sales $sales)
    {
        dd(session()->get('quantity'));

        // // product_id
        // $product = Products::with('product_quantity')->TotalQuantity()->find($product_id);
        // if ($product->total_quantity > 0) {
        //     $sale_details->quantity = $sale_details->quantity + 1;
        //     $sale_details->save();
        //     $min_expiry_date = $this->checkProductExpiryDate($product);
        //     $quantity = $product->product_quantity->where('expiry_date', $min_expiry_date)->first();
        //     $quantity->update([
        //         'quantity' => $quantity->quantity - 1
        //     ]);
        //     $sales->total = $sales->total + $product->sale_price;
        //     $sales->save();
        //     $this->dispatchBrowserEvent('play', ['sound' => 'beep']);
        //     flash()->addSuccess(__('header.add'));
        // } else {
        //     $this->dispatchBrowserEvent('play', ['sound' => 'fail']);
        //     flash()->addError(__('header.out_of_stock'));
        // }
        // $this->reset();
        // $this->render();
    }
    public function minus(sale_details $sale_details, $product_id, Sales $sales)
    {
        $product = Products::with('product_quantity')->TotalQuantity()->SalePrice()->find($product_id);
        $sales->total = $sales->total - $product->final_sale_price;
        $sales->total < 0 ?  0 : $sales->total;
        $sales->save();
        $sale_details->quantity = $sale_details->quantity - 1;
        $sale_details->save();
        // get all product_quantity
        $quantitys = (array)  json_decode(session()->get('quantity'));
        // change to array
        // $product_quantity = $product->product_quantity;
        // $product_quantity = $product->product_quantity;
        // dd($product_quantity, $quantitys);
        $product_quantity = null;
        foreach ($product->product_quantity as $product) {
            if (array_key_exists($product->id, $quantitys)) {
                if ($quantitys[$product->id]->expiry_date == $product->expiry_date) {
                    $product_quantity = $product;
                    break;
                }
            }
        }
        // dd($product_quantity);
        if ($product_quantity) {
           $quantitys[$product_quantity->id]->quantity = $quantitys[$product_quantity->id]->quantity - 1;
            $this->sessionSet($quantitys[$product_quantity->id]->quantity, $product_quantity->id, $product_quantity->expiry_date);
            // update product by expiry_date
            $product_quantity->update([
                'quantity' => $product_quantity->quantity + 1
            ]);
            if ($sale_details->quantity == 0) {
                $sale_details->delete();
                flash()->addWarning(__('header.deleted'));
            }
            $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
            $this->reset();
        }
        // // check a min_expiry date inside  a  $quantitys value

        // $quantity = $product->product_quantity->where('expiry_date', $min_expiry_date)->first();
        // // check quantitys by quantity->id if have update the quantitys->id->quantity -1
        // if (array_key_exists($quantity->id, $quantitys)) {
        //     $quantitys[$quantity->id]->quantity = $quantitys[$quantity->id]->quantity - 1;
        //     $this->sessionSet($quantitys[$quantity->id]->quantity, $quantity->id, $quantity->expiry_date);
        //     $quantity->update([
        //         'quantity' => $quantity->quantity + 1
        //     ]);
        //     if ($sale_details->quantity == 0) {
        //         $sale_details->delete();
        //         flash()->addWarning(__('header.deleted'));
        //     }
        // }

        // dd($quantity);
        // $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
        // $this->reset();
    }
    public function destroy(sale_details $sale_details, $product_id, Sales $sales)
    {
        $product = Products::with('product_quantity')->TotalQuantity()->SalePrice()->find($product_id);
        if (!$sale_details && !$product && !$sales) {
            return;
        }
        $sales->total = $sales->total - ($product->final_sale_price * $sale_details->quantity);
        $sales->total < 0 ?  0 : $sales->total;
        $sales->save();
        $min_expiry_date = $product->product_quantity->min('expiry_date');
        $quantity = $product->product_quantity->where('expiry_date', $min_expiry_date)->first();
        $quantity->update([
            'quantity' => $quantity->quantity + $sale_details->quantity
        ]);
        $sale_details->delete();
        $this->dispatchBrowserEvent('play', ['sound' => 'undo']);
        flash()->addWarning(__('header.deleted'));
        $this->reset();
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
        $sales = Sales::where('invoice',  session('invoice'))->first();
        if ($sales == null || $sales->total == 0) {
            return;
        }
        $this->saleID = $sales->id;
        $this->discount = $this->discount ?? 0;
        if ($this->discount > 0 && $this->discount <= 100) {
            $total = $sales->total - ($sales->total * $this->discount / 100);
        } else {
            $total = $sales->total - $this->discount;
        }
        $sales->update([
            'user_id' => auth()->user()->id,
            'customer_id' => $this->customer,
            'supplier_id' => $this->supplier,
            'status' => 1,
            'total' => $total,
            'discount' => $this->discount,
            'paid' => $this->debt ? 0 : 1,
        ]);
        if ($this->debt) {
            $sales->debt_sale()->create([
                'customer_id' => $this->customer,
                'amount' => $sales->total,
                'paid' => $this->currentpaid ?? 0,
                'remain' => $sales->total - $this->currentpaid,
            ]);
        }
        $this->customer ? $customer = Customers::select(['name', 'phone'])->find($this->customer) : $customer = null;
        $data = [
            'invoice : ' . $sales->invoice,
            'total Price : ' . (number_format($sales->total, 0, ',', ',')),
            'discount : '  . (number_format($sales->discount, 0, ',', ',')),
            'debt : ' . ($this->debt ? 'yes' : 'no'),
            'name : ' . ($customer?->name),
            'phone : ' . ($customer?->phone),
            'currentpaid : ' . ($this->currentpaid ? number_format($this->currentpaid, 2, ',', ',') : 'no paid'),
        ];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Sale", 'Sale', 'nothing to show', $data);
        session()->forget('invoice');
        if ($check) {
            flash()->addSuccess(__('header.successSale'));
            return redirect()->route('sales', app()->getLocale());
        }
    }
    public function salePrint()
    {
        $this->submit(false);
        $route = route('sales.print', ['lang' => app()->getLocale(), 'id' => $this->saleID]);
        $this->dispatchBrowserEvent('print', ['route' => $route]);
    }

    public function addSupplier()
    {
        $this->validate(
            [
                'name' => 'required|string',
                'phone' => 'required|numeric|digits:11|unique:suppliers,phone',
                'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|unique:suppliers,email',
                'address' => 'required|string',
            ],
            [
                'name.required' => __('validation.required', ['attribute' => __('header.name')]),
                'name.string' => __('validation.string', ['attribute' => __('header.name')]),
                'email.required' => __('validation.required', ['attribute' => __('header.email')]),
                'email.email' => __('validation.email', ['attribute' => __('header.email')]),
                'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
                'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
                'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
                'phone.numeric' => __('validation.numeric', ['attribute' => __('header.phone')]),
                'phone.digits' => __('validation.digits', ['attribute' => __('header.phone')]),
                'phone.unique' => __('validation.unique', ['attribute' => __('header.phone')]),
                'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            ]
        );
        $supplier  = Suppliers::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);
        $data = [
            'name : ' . $supplier->name,
            'email : ' . $supplier->email,
            'phone : ' . $supplier->phone,
            'address : ' . $supplier->address,
        ];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Create', 'nothing to show', $data);
        flash()->addSuccess(__('header.add'));
        $this->done();
    }

    public function addCustomer($action = false)
    {

        $this->validate(
            [
                'name' => 'required|string',
                'phone' => 'required|numeric|digits:11|unique:suppliers,phone',
                'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|unique:customers,email',
                'address' => 'required|string',
                'guarantorphone' => 'nullable|numeric',
                'guarantoraddress' => 'nullable|string',
            ],
            [
                'name.required' => __('validation.required', ['attribute' => __('header.name')]),
                'name.string' => __('validation.string', ['attribute' => __('header.name')]),
                'email.required' => __('validation.required', ['attribute' => __('header.email')]),
                'email.email' => __('validation.email', ['attribute' => __('header.email')]),
                'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
                'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
                'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
                'phone.numeric' => __('validation.numeric', ['attribute' => __('header.phone')]),
                'phone.digits' => __('validation.digits', ['attribute' => __('header.phone')]),
                'phone.unique' => __('validation.unique', ['attribute' => __('header.phone')]),
                'address.required' => __('validation.required', ['attribute' => __('header.address')]),
                'guarantorphone.numeric' => __('validation.numeric', ['attribute' => __('header.guarantorphone')]),
                'guarantorphone.digits' => __('validation.digits', ['attribute' => __('header.guarantorphone')]),
                'guarantoraddress.string' => __('validation.string', ['attribute' => __('header.guarantoraddress')]),
            ]
        );
        $supplier  = Customers::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'guarantorphone' => $this->guarantorphone ?? 0,
            'guarantoraddress' => $this->guarantoraddress ?? 0,
        ]);
        $data = [
            'name : ' . $supplier->name,
            'email : ' . $supplier->email,
            'phone : ' . $supplier->phone,
            'address : ' . $supplier->address,
            'guarantorphone : ' . $supplier->guarantorphone,
            'guarantoraddress : ' . $supplier->guarantoraddress,
        ];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Customer", 'Create', 'nothing to show', $data);
        flash()->addSuccess(__('header.add'));
        if (!$action)
            $this->done();
    }

    public function checkProductExpiryDate($product)
    {
        $check = $product;
        $product = $product->first();
        $min_expiry_date = $product->product_quantity->min('expiry_date');
        // check a min_expiry date <= now() if true will be change a min_expiry date
        $min_expiry_date_data = [];
        if ($min_expiry_date <= now()) {
            foreach ($product->product_quantity as $product_quantity) {
                if ($product_quantity->expiry_date > now()) {
                    $min_expiry_date_data[] = $product_quantity;
                }
            }
        }
        if (count($min_expiry_date_data) > 0) {
            uasort($min_expiry_date_data, function ($a, $b) {
                return $a->expiry_date <=> $b->expiry_date;
            });
            // get min expiry date from product_expiry_dates
            $product_expiry_dates = array_values($min_expiry_date_data);
            $product_expiry_dates  = collect($product_expiry_dates);
            $min_expiry_date =  $product_expiry_dates->min('expiry_date');
            $checkProduct = $check->first()->product_quantity->where('expiry_date', $min_expiry_date)->first();
            if ($checkProduct->quantity == 0) {
                $min_expiry_date = $check->first()->product_quantity->where('id', '!=', $checkProduct->id)->where('expiry_date', '>', now())->where('quantity', '!=', 0)->first()->expiry_date;
            }
        }
        return $min_expiry_date;
        // return $product_expiry_dates;
    }
    public function sessionSet($quantity, $index, $expiry_date)
    {
        $quantitys = json_decode(session()->get('quantity'));
        // change to array
        $data = (array) $quantitys;
        if ($quantity == 0) {
            unset($data[$index]);
        } else {
            // chec data if index exist or not if exist update a quantity
            if (array_key_exists($index, $data)) {
                $data[$index]->quantity = $quantity + $data[$index]->quantity;
            } else {
                $data[$index] =
                    [
                        'quantity' => $quantity,
                        'expiry_date' => $expiry_date
                    ];
            }
        }
        // update session
        $data = json_encode($data);
        session()->put('quantity', $data);
    }

    // public function toCheckQuantitys($product)
    // {
    //     $quantitys = json_decode(session()->get('quantity'));
    //     // change to array
    //     $quantitys = (array) $quantitys;
    //     $min_expiry_date = $this->checkProductExpiryDate($product);
    //     $quantity = $product->product_quantity->where('expiry_date', $min_expiry_date)->first();
    //     // check quantitys by quantity->id if have update the quantitys->id->quantity -1
    //     if (array_key_exists($quantity->id, $quantitys)) {
    //         $quantitys[$quantity->id]->quantity = $quantitys[$quantity->id]->quantity - 1;
    //         $this->sessionSet($quantitys[$quantity->id]->quantity, $quantity->id, $quantity->expiry_date);
    //         return $quantity->expiry_date;
    //     } else {
    //         $min_expiry_date = $this->checkProductExpiryDate($product);
    //         $quantity = $product->product_quantity->where('expiry_date', $min_expiry_date)->first();
    //     }
    // }
}
