<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Livewire\Component;

class NewUsers extends Component
{
    public function render()
    {
        $TotalUsers = User::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        return view('dashboard.new-users' , compact('TotalUsers'));
    }
}
