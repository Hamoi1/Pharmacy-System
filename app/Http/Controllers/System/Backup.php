<?php

namespace App\Http\Controllers\System;

use Livewire\Component;
use App\Notifications\Sendfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class Backup extends Component
{
    public $email;
    public function render()
    {
        return view('system.backup');
    }


    public function submit()
    {
        $this->validate([
            'email' => 'required|email',
        ], [
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
        ]);
        $email = $this->email;
        // run <backup></backup>
        exec(
            'php ' . base_path() . '/artisan backup:run --only-db --disable-notifications',
            $output,
            $return
        );
        // get last backup file
        $files = Storage::disk('local')->files('backup');
        $lastFile = end($files);
        $files = collect($files);
        foreach ($files as $file) {
            if ($file == $lastFile) {
                continue;
            } else {
                Storage::disk('local')->delete($file);
            }
        }

        $file = Storage::disk('local')->get($lastFile);
        // send file to email
        Notification::route('mail', $email)->notify(new Sendfile($file));
        $this->reset('email');
        flash()->addSuccess(__('header.backupSuccessfully'));
        $this->dispatchBrowserEvent('closeModal');
    }
}

// auth()->user()->notify(new \App\Notifications\Sendfile($file));