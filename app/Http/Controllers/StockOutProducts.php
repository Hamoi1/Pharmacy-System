<?php

namespace App\Http\Controllers;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class StockOutProducts extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public function mount()
    {
        if (!Gate::allows('View StockOutProduct')) {
            abort(404);
        }
    }
    public function render()
    {
        $products = \App\Models\Products::where('quantity', '=', 0)->paginate(15);
        return view('stock-out-products', ['products' => $products]);
    }
}
