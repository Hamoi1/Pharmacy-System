<?php

namespace App\Http\Controllers\Dashboard;

use Livewire\Component;

class ExpiryProduct extends Component
{
    public function render()
    {
        $ExpiryCount = \App\Models\Products::where('expiry_date', '<=', now()->format('Y-m-d'))->count();
        return view('dashboard.expiry-product', compact('ExpiryCount'));
    }
}
