<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Categorys;
use Livewire\Component;

class NewCategorys extends Component
{

    public function render()
    {
        $TotalCategorys = Categorys::Wheredate('created_at', '=', now()->format('Y-m-d'))->count();
        return view('dashboard.new-categorys', compact('TotalCategorys'));
    }
}
