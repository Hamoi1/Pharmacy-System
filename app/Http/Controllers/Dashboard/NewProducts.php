<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Products;
use Livewire\Component;

class NewProducts extends Component
{

    public function render()
    {
        $TotalProducts = Products::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        return view('dashboard.new-products', compact('TotalProducts'));
    }
}
