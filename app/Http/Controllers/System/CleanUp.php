<?php

namespace App\Http\Controllers\System;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class CleanUp extends Component
{
    public function render()
    {
        return view('system.clean-up');
    }
    public function CleanUp()
    {
        $File = Storage::disk('debugbar');
        if ($File) {
            $File->deleteDirectory(''); // delete all files in debugbar folder
        }
        $oldFiles = Storage::files('livewire-tmp');
        foreach ($oldFiles as $file) {
            Storage::delete($file);
        }
        flash()->addSuccess('header.cleaned');
    }
}
