<?php

namespace App\Http\Controllers\Dashboard;

use Livewire\Component;

class TotalUsers extends Component
{
    public function render()
    {
        $UsersCount = \App\Models\User::count();
        return view('dashboard.total-users' , compact('UsersCount'));
    }
}
