<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Sales;
use Livewire\Component;

class TodaySaleProduct extends Component
{
    public function render()
    {
        $TotalSaleProduct = Sales::Wheredate('created_at', now()->format('Y-m-d'))->count();
        return view('dashboard.today-sale-product', compact('TotalSaleProduct'));
    }
}
