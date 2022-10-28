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
    public $name, $phone, $email, $address, $logo, $oldLogo;
    public  $setting;
    public function mount()
    {
        if (!Gate::allows('admin')) {
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
        return view('setting');
    }
    public function GetRuls()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:11',
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|max:20000|mimes:jpg,jpeg,png,gif,svg,webp,ico',
        ];
    }
    public function GetMessage()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('header.settings.name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.settings.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('header.settings.name'), 'max' => 255]),
            'phone.required' => __('validation.required', ['attribute' => __('header.settings.phone')]),
            'phone.numeric' => __('validation.numeric', ['attribute' => __('header.settings.phone')]),
            'phone.digits' => __('validation.digits', ['attribute' => __('header.settings.phone'), 'digits' => 11]),
            'email.required' => __('validation.required', ['attribute' => __('header.settings.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.settings.email')]),
            'address.required' => __('validation.required', ['attribute' => __('header.settings.address')]),
            'address.string' => __('validation.string', ['attribute' => __('header.settings.address')]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.settings.address'), 'max' => 255]),
            'logo.image' => __('validation.image', ['attribute' => __('header.image')]),
            'logo.max' => __('validation.max.file', ['attribute' => __('header.image'), 'max' => 20000]),
            'logo.mimes' => __('validation.mimes', ['attribute' => __('header.image'), 'values' => 'jpg,jpeg,png,gif,svg,webp,ico']),
        ];
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        if ($this->logo) {
            $logo = Image::make($this->logo)->resize(250, 250, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $logoName = time() . '-' . uniqid() . '.' . $this->logo->getClientOriginalExtension();
            Storage::disk('public')->put('logo/' . $logoName, $logo->stream());
        }
        $this->setting->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'logo' => $logoName ?? $this->oldLogo,
        ]);
        flash()->addSuccess('header.updated');
        $this->reset('logo');
        $this->resetValidation();
        $this->mount();
    }
}
