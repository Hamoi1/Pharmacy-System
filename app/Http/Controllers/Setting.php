<?php

namespace App\Http\Controllers;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;


class Setting extends Component
{
    use WithFileUploads;
    public $name, $phone, $email, $address, $logo, $oldLogo, $exchangeRate;
    public  $setting;
    protected $listeners = ['ChangeTheme' => '$refresh'];
    public function mount()
    {
        if (!Gate::allows('View Setting')) {
            abort(404);
        }
    }
    public function render()
    {
        $this->setting = \App\Models\Settings::first();
        $this->name = $this->setting->name;
        $this->phone = $this->setting->phone;
        $this->email = $this->setting->email;
        $this->address = $this->setting->address;
        $this->oldLogo = $this->setting->logo;
        $this->exchangeRate = $this->setting->exchange_rate;
        return view('setting');
    }
    public function submit()
    {
        $this->exchangeRate = str_replace([',', '.'], '', $this->exchangeRate);
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:11',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|max:20000|mimes:jpg,jpeg,png,gif,svg,webp,ico',
            'exchangeRate' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/'
        ], [
            'name.required' => __('validation.required', ['attribute' => __('header.settings.name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.settings.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('header.settings.name'), 'max' => 255]),
            'phone.required' => __('validation.required', ['attribute' => __('header.settings.phone')]),
            'phone.numeric' => __('validation.numeric', ['attribute' => __('header.settings.phone')]),
            'phone.digits' => __('validation.digits', ['attribute' => __('header.settings.phone'), 'digits' => 11]),
            'email.required' => __('validation.required', ['attribute' => __('header.settings.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.settings.email')]),
            'email.regex' => __('validation.regex', ['attribute' => __('header.settings.email')]),
            'address.required' => __('validation.required', ['attribute' => __('header.settings.address')]),
            'address.string' => __('validation.string', ['attribute' => __('header.settings.address')]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.settings.address'), 'max' => 255]),
            'logo.image' => __('validation.image', ['attribute' => __('header.image')]),
            'logo.max' => __('validation.max.file', ['attribute' => __('header.image'), 'max' => 20000]),
            'logo.mimes' => __('validation.mimes', ['attribute' => __('header.image'), 'values' => 'jpg,jpeg,png,gif,svg,webp,ico']),
            'exchangeRate.required' => __('validation.required', ['attribute' => __('header.exchangeRate')]),
            'exchangeRate.numeric' => __('validation.numeric', ['attribute' => __('header.exchangeRate')]),
            'exchangeRate.min' => __('validation.min.numeric', ['attribute' => __('header.exchangeRate'), 'min' => 0]),
            'exchangeRate.regex' => __('validation.regex', ['attribute' => __('header.exchangeRate')]),
        ]);
        if ($this->logo) {
            $logoName = time() . '-' . uniqid() . '.' . $this->logo->getClientOriginalExtension();
            $this->logo->storeAs('public/logo', $logoName);
            if ($this->oldLogo) {
                Storage::delete('public/logo/' . $this->oldLogo);
            }
        }
        $this->setting->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'logo' => $logoName ?? $this->oldLogo,
            'exchange_rate' => $this->exchangeRate,
        ]);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.updated')]);
        $this->resetValidation();
    }

    public function ChangeTheme($theme)
    {
        if ($theme == 1) {
            auth()->user()->update([
                'theme' => 0,
            ]);
        } elseif ($theme == 0) {
            auth()->user()->update([
                'theme' => 1,
            ]);
        }
        $this->emitSelf('ChangeTheme');
        $this->emit('ChangeTheme', $this->setting->theme);
    }
}
