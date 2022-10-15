<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ViewSale extends Component
{
    public $saleId, $invoice, $sale;
    public function mount($lang, $id, $invoice)
    {

        if (!Gate::allows('admin')) {
            abort(404);
        }
        $this->sale = Sales::findOrFail($id)->where('invoice', $invoice)->with('sale_details', function ($q) {
            $q->product();
        })->with('debt_sale')->first();
    }
    public function render()
    {
        return view('sales.view-sale');
    }
}
