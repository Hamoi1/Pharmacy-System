<?php

namespace App\Http\Controllers\Dashboard;

use Livewire\Component;

class TotalProduct extends Component
{
    public function render()
    {
        $ProductCount = \App\Models\Products::count();
        return view('dashboard.total-product' , compact('ProductCount'));
    }
}
