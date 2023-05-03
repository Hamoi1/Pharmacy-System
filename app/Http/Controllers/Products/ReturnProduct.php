<?php

namespace App\Http\Controllers\Products;

use App\Models\Sales;
use Livewire\Component;
use App\Models\Products;
use App\Models\Customers;
use Livewire\WithPagination;

class ReturnProduct extends Component
{
    use WithPagination;
    public $barcodeOrname, $customer, $quantity = 1, $products, $productId, $customers, $CustomerSales, $CustomerSearch, $SaleId;
    protected $queryString = [
        'customer' => ['except' => '', 'as' => 'customer'],
        'barcodeOrname' => ['except' => '', 'as' => 'product'],
    ];
    public function render()
    {
        // use Currency
        // $formattedValue = Currency::convert()->from('USD')->to('IQD')->amount(1)->get();
        // dd($formattedValue);
        $this->customers = Customers::query();
        if ($this->CustomerSearch) {
            $this->customers =  $this->customers->select('id', 'name')->where('name', 'like', '%' . $this->CustomerSearch . '%')->get();
        } else {
            $this->customers = $this->customers->select('id', 'name')->get();
        }
        return view('products.return.return-product');
    }

    public function done($action = true)
    {
        $this->reset(['SaleId', 'products']);
        $this->resetValidation();
    }
    public function updatedbarcodeOrname()
    {
        if (is_numeric($this->barcodeOrname)) {
            $this->validate(
                ['barcodeOrname' => 'required|exists:products,barcode'],
                [
                    'barcodeOrname.required' => __('validation.required', ['attribute' => __('header.barcode')]),
                    'barcodeOrname.exists' =>  __('validation.exists', ['attribute' => __('header.barcode')]),
                ]
            );
            $this->products  = Products::where('barcode', $this->barcodeOrname)->first();
        } else {
            $this->barcodeOrname != null && $this->barcodeOrname != "" ?  $this->products = Products::where('name', 'like', '%' . $this->barcodeOrname . '%')->get() : $this->products = null;
        }
    }
    public function ReturnProducts()
    {
        $this->products  = null;
        $this->customers  = null;
        $this->validate(
            [
                'barcodeOrname' => 'required',
                'customer' => 'required|exists:customers,id',
                'quantity' => 'required|numeric',
            ],
            [
                'barcodeOrname.required' => __('validation.required', ['attribute' => __('header.barcodeOrname')]),
                'customer.required' => __('validation.required', ['attribute' => __('header.Customer')]),
                'customer.exists' =>      __('validation.exists', ['attribute' => __('header.Customer')]),
                'quantity.required' => __('validation.required', ['attribute' => __('header.quantity')]),
                'quantity.numeric' => __('validation.numeric', ['attribute' => __('header.quantity')]),
            ]
        );

        if ($this->CustomerSales == null) {
            $this->addError('barcodeOrname', __('header.Customerdidnotpurchase'));
        } else {
            if (!$this->SaleId) {
                $this->addError('selectSale', __('header.PleaseSelectSale') . '.');
            } else {
                // get sale by saleId and with debt_sale and sale_details and where sale_details.product_id = $prodcut_id                
                $CustomerSale = Sales::with(['debt_sale', 'sale_details' => function ($query) {
                    $query->where('product_id', $this->productId);
                }])->findOrFail($this->SaleId);
                $product = Products::with('product_quantity')->SalePrice()->findOrFail($this->productId);
                if ($CustomerSale->sale_details[0]->product_id === $this->productId) {
                    if ($CustomerSale->sale_details[0]->quantity < $this->quantity || $this->quantity == 0) {
                        $this->addError('quantity', __('validation.exists', ['attribute' => __('header.returnQuantity')]));
                    } else {
                        $CustomerSale->sale_details[0]->quantity -= $this->quantity;
                        $CustomerSale->sale_details[0]->save();
                        $minExpiryDate = $product->product_quantity->min('expiry_date');
                        $productQuantity = $product->product_quantity->where('expiry_date', $minExpiryDate)->first();
                        $productQuantity->quantity += $this->quantity;
                        $productQuantity->save();
                        $CustomerSale->total -= ($product->sale_price * $this->quantity);
                        $CustomerSale->save();
                        if ($CustomerSale->debt_sale && $CustomerSale->debt_sale->status != 1) {
                            $CustomerSale->debt_sale->amount = $CustomerSale->total;
                            $CustomerSale->debt_sale->remain =  $CustomerSale->debt_sale->amount - $CustomerSale->debt_sale->paid;
                            $CustomerSale->debt_sale->save();
                            if ($CustomerSale->debt_sale->remain == 0) {
                                $CustomerSale->debt_sale->status = 1;
                                $CustomerSale->debt_sale->paid = $CustomerSale->debt_sale->amount;
                                $CustomerSale->debt_sale->save();
                                $CustomerSale->paid = 1;
                                $CustomerSale->save();
                            }
                        }
                        $CustomerSale->total == 0 || $CustomerSale->total <= 0 ? $CustomerSale->delete() : null;
                        if ($CustomerSale->sale_details[0]->quantity == 0) {
                            $CustomerSale->sale_details[0]->delete();
                        }
                        flash()->addSuccess(__('header.returnProductSuccessfully'));
                        $this->search();
                        $this->done();
                    }
                }
            }
        }
    }
    public function search()
    {
        $this->validate([
            'customer' => 'required|exists:customers,id',
            'barcodeOrname' => 'required',
        ], [
            'barcodeOrname.required' => __('validation.required', ['attribute' => __('header.barcodeOrname')]),
            'customer.required' => __('validation.required', ['attribute' => __('header.Customer')]),
            'customer.exists' =>  __('validation.exists', ['attribute' => __('header.Customer')]),
        ]);
        $this->products  = null;
        $this->customers  = null;
        if (is_numeric($this->barcodeOrname)) {
            $this->productId = Products::select(['id', 'name', 'barcode', 'quantity', 'sale_price'])->where('barcode', $this->barcodeOrname)->first()->id;
        } else {
            $this->productId = Products::select(['id', 'name', 'barcode', 'quantity', 'sale_price'])->where('name', $this->barcodeOrname)->first()->id;
        }
        $customer = Customers::FindOrFail($this->customer);
        $productId = $this->productId;
        $this->CustomerSales = Sales::with([
            'sale_details' => function ($query) use ($productId) {
                $query->with('products')->where('product_id', $productId);
            },
            'debt_sale'
        ])->where('customer_id', $customer->id)
            ->whereHas('sale_details', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->get();
        $this->SaleId = null;
    }
}
