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
        $livewireFiles = Storage::files('livewire-tmp');
        foreach ($livewireFiles as $file) {
            Storage::delete($file);
        }
        // delete all files in Pharmacy-backup folder
        $Pharmacy_backup_Files = Storage::files('Pharmacy-backup');
        foreach ($Pharmacy_backup_Files as $file) {
            Storage::delete($file);
        }
        $file = fopen(storage_path('logs/laravel.log'), 'w');
        fclose($file);
        flash()->addSuccess(__('header.cleaned'));
    }
}
