<?php

namespace App\Http\Controllers;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Dashboard extends Component
{
    public function render()
    {
        if (!Gate::allows('admin')) {
            redirect()->route('sales', app()->getLocale());
        }
        $users = User::withCount('sales')->orderBy('sales_count', 'desc')->take(10)->get();
        return view('dashboard', ['users' => $users]);
    }
}
