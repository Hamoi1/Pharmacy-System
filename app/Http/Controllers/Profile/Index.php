<?php

namespace App\Http\Controllers\Profile;

use Livewire\Component;

class Index extends Component
{

    protected
        $listeners = ['UpdateProfile' => 'render'];
    public $Page_Edit_Profile;
    public function render()
    {
        if (request()->routeIs('profile.update')) {
            $this->Page_Edit_Profile = true;
        }

        return view('profile.index');
    }
}
