<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Sales;
use Livewire\Component;
use App\Models\sale_details;

class TodaySale extends Component
{
    public function render()
    {
        $TotalSalePrice = Sales::whereDate('created_at', now()->format('Y-m-d'))->sum('total');;
        return view('dashboard.today-sale', compact('TotalSalePrice'));
    }
}
