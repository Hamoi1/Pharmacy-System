<?php

namespace App\Http\Controllers\Dashboard;

use Livewire\Component;

class StockOutProduct extends Component
{
    public function render()
    {
        $StockedOutProduct = \App\Models\Products::where('quantity', '=', 0)->count();
        return view('dashboard.stock-out-product' , compact('StockedOutProduct'));
    }
}
