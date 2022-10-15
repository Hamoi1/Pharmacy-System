<?php

namespace App\Http\Controllers;

use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Dashboard extends Component
{
    public function render()
    {
        if (!Gate::allows('admin')) {
            abort(404);
        }
        return view('dashboard');
    }
}
