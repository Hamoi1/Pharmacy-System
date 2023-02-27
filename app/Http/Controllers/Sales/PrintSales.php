<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class PrintSales extends Component
{
    public $saleId, $invoice, $sale;
    public function mount($lang, $id, $invoice)
    {
        $this->sale = Sales::findOrFail($id)->where('invoice', $invoice)->with('sale_details', function ($q) {
            $q->whereNotNull('product_id')->product();
        })->with('debt_sale')->first();
    }
    public function render()
    {
        return view('sales.PrintSales');
    }
}
