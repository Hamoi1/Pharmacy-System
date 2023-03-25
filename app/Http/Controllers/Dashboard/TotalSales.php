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
        $this->TotalSale = \App\Models\Sales::whereDate('created_at', $this->date)->count();
        $this->TotalSaleProduct = \App\Models\sale_details::whereDate('created_at', $this->date)->sum('quantity');
        $this->TotalSalesPrice = \App\Models\Sales::whereDate('created_at', $this->date)->sum('total');
    }
    public function updateddate()
    {
        $this->mount();
    }
}
