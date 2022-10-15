<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Suppliers;
use Livewire\Component;

class NewSuppliers extends Component
{

    public function render()
    {
        $TotalSuppliers = Suppliers::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        return view('dashboard.new-suppliers', compact('TotalSuppliers'));
    }
}
