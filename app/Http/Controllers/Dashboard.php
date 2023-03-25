<?php

namespace App\Http\Controllers;

use finfo;
use App\Models\User;
use Livewire\Component;
use App\Models\sale_details;
use Illuminate\Support\Facades\Gate;

class Dashboard extends Component
{
    public function render()
    {
        if (!Gate::allows('View Dashboard')) {
            redirect()->route('sales', app()->getLocale());
        }
        $users = User::withCount('sales')->orderBy('sales_count', 'desc')->take(10)->get();
        $products = sale_details::whereHas('products', function ($query) {
            return $query->whereNull('products.deleted_at');
        })->with('products')->whereNotNull('product_id')->selectRaw('sum(quantity) as total_quantity, product_id')
            ->groupBy('product_id')->orderBy('total_quantity', 'desc')->take(10)->get();;


        return view('dashboard', ['users' => $users, 'products' => $products]);
    }

}
