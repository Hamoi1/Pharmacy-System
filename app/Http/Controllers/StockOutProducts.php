<?php

namespace App\Http\Controllers;

use Livewire\Component;
use Livewire\WithPagination;

class StockOutProducts extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $products = \App\Models\Products::where('quantity', '=', 0)->paginate(15);
        return view('stock-out-products', ['products' => $products]);
    }
}
