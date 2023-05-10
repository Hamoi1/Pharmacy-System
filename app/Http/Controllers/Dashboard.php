<?php

namespace App\Http\Controllers;

use App\Models\Categorys;
use App\Models\Products;
use App\Models\User;
use Livewire\Component;
use App\Models\sale_details;
use App\Models\Sales;
use App\Models\Suppliers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class Dashboard extends Component
{
    public $date;

    protected $queryString = ['date'];
    public function render()
    {
        if (!Gate::allows('View Dashboard')) {
            redirect()->route('sales', app()->getLocale());
        }
        // $users = DB::table('users')->
        // withCount('sales')->
        // orderBy('sales_count', 'desc')->take(10)->get();
        // $products = sale_details::whereHas('products', function ($query) {
        //     return $query->whereNull('products.deleted_at');
        // })->with('products')->whereNotNull('product_id')->selectRaw('sum(quantity) as total_quantity, product_id')
        //     ->groupBy('product_id')->orderBy('total_quantity', 'desc')->take(10)->get();
        $TotalProducts = Products::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        $TotalUsers = User::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        $TotalSuppliers = Suppliers::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        $TotalCategorys = Categorys::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        $sale  = Sales::query();
        $TotalSalePrice = $sale->whereDate('created_at', now()->format('Y-m-d'))->sum('total');
        $TotalSale = Sales::query();
        $TotalSoldProduct = $TotalSale->Wheredate('created_at', now()->format('Y-m-d'))->count();

        if ($this->date == null) {
            $this->date = now()->format('Y-m-d');
        } elseif ($this->date > now()) {
            $this->date = now()->format('Y-m-d');
        }
        $TotalSale = $TotalSale->whereDate('created_at', $this->date)->count();
        $TotalSaleProduct = \App\Models\sale_details::whereDate('created_at', $this->date)->sum('quantity');
        $TotalSalesPrice = $sale->whereDate('created_at', $this->date)->sum('total');
        $ProductCount = \App\Models\Products::count();
        $UsersCount = \App\Models\User::count();

        return view(
            'dashboard',
            compact(
                'TotalProducts',
                'TotalUsers',
                'TotalSuppliers',
                'TotalCategorys',
                'TotalSalePrice',
                'TotalSoldProduct',
                'TotalSale',
                'TotalSalesPrice',
                'TotalSaleProduct',
                'ProductCount',
                'UsersCount'
            )
        );
    }
}
