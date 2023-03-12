<?php

namespace App\Http\Controllers\Dashboard;

use Livewire\Component;

class TotalSales extends Component
{
    public $TotalSale = 0, $TotalSaleProduct = 0, $TotalSalesPrice = 0, $date;
    protected $queryString = ['date'];
    public function render()
    {
        return view('dashboard.total-sales');
    }
    public function mount()
    {
        if($this->date == null){
            $this->date = now()->format('Y-m-d');
        }elseif($this->date > now()){
            $this->date = now()->format('Y-m-d');
        }
        // if ($this->sales == 'today') {
        //     $this->TotalSale = \App\Models\Sales::WhereDate('created_at', now())->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::WhereDate('created_at', now())->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::WhereDate('created_at', now())->sum('total');
        // } elseif ($this->sales == 'Yesterday') {
        //     $this->TotalSale = \App\Models\Sales::whereDate('created_at', now()->subDay()->format('Y-m-d'))->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereDate('created_at', now()->subDay()->format('Y-m-d'))->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereDate('created_at', now()->subDay()->format('Y-m-d'))->sum('total');
        // } elseif ($this->sales == 'ThisWeek') {
        //     $this->TotalSale = \App\Models\Sales::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total');
        // } elseif ($this->sales == 'ThisMonth') {
        //     $this->TotalSale = \App\Models\Sales::whereMonth('created_at', now()->month)->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereMonth('created_at', now()->month)->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereMonth('created_at', now()->month)->sum('total');
        // } elseif ($this->sales == '3-month-ago') {
        //     $this->TotalSale = \App\Models\Sales::whereMonth('created_at', now()->subMonth(3)->month)->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereMonth('created_at', now()->subMonth(3)->month)->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereMonth('created_at', now()->subMonth(3)->month)->sum('total');
        // } elseif ($this->sales == '6-month-ago') {
        //     $this->TotalSale = \App\Models\Sales::whereMonth('created_at', now()->subMonth(6)->month)->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereMonth('created_at', now()->subMonth(6)->month)->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereMonth('created_at', now()->subMonth(6)->month)->sum('total');
        // } elseif ($this->sales == 'ThisYear') {
        //     $this->TotalSale = \App\Models\Sales::whereYear('created_at', now()->year)->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereYear('created_at', now()->year)->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereYear('created_at', now()->year)->sum('total');
        // } elseif ($this->sales == 'one-year-ago') {
        //     $this->TotalSale = \App\Models\Sales::whereYear('created_at', now()->subYear()->year)->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereYear('created_at', now()->subYear()->year)->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereYear('created_at', now()->subYear()->year)->sum('total');
        // } elseif ($this->sales == '3-year-ago') {
        //     $this->TotalSale = \App\Models\Sales::whereYear('created_at', now()->subYear(3)->year)->count();
        //     $this->TotalSaleProduct = \App\Models\sale_details::whereYear('created_at', now()->subYear(3)->year)->sum('quantity');
        //     $this->TotalSalesPrice = \App\Models\Sales::whereYear('created_at', now()->subYear(3)->year)->sum('total');
        // }
        $this->TotalSale = \App\Models\Sales::whereDate('created_at', $this->date)->count();
        $this->TotalSaleProduct = \App\Models\sale_details::whereDate('created_at', $this->date)->sum('quantity');
        $this->TotalSalesPrice = \App\Models\Sales::whereDate('created_at', $this->date)->sum('total');
    }
    public function updateddate()
    {
        $this->mount();
    }
}
