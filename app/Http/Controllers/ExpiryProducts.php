<?php

namespace App\Http\Controllers;

use Livewire\Component;
use App\Models\Products;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class ExpiryProducts extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function mount()
    {
        if (!Gate::allows('admin')) {
            abort(404);
        }
    }
    public function render()
    {
        // return all expiry products
        $products = Products::where('expiry_date', '<=', now())->paginate(15);
        return view('expiry-products', compact('products'));
    }
}
