<?php

namespace App\Http\Controllers\Profile;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['UpdateProfile' => 'render'];
    public function render()
    {
        return view('profile.index');
    }
}
