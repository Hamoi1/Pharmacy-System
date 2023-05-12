<?php

namespace App\Http\Controllers\Profile;

use Livewire\Component;

class Index extends Component
{
    public $changeProfile;
    public function render()
    {
        return view('profile.index');
    }
    // public function updatedchangeProfile()
    // {
    //     $this->render();
    // }
}
