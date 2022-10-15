<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\sale_details;
use Livewire\Component;

class TodaySale extends Component
{
    public function render()
    {
        $TotalSalePrice = sale_details::Wheredate('created_at', '=', now()->format('Y-m-d'))->sum('quantity');
        return view('dashboard.today-sale', compact('TotalSalePrice'));
    }
}
