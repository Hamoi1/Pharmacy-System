<?php

namespace App\Http\Controllers\Profile;

use App\Events\UserPage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Update extends Component
{
    use WithFileUploads;
    public $image, $name, $username, $email, $phone, $address, $password, $confirm_password;
    protected $listeners = ['user-page' => '$refresh'];
    public function render()
    {
        return view('profile.update');
    }
    public function updatedImage()
    {
        $this->validate([
            'image' => 'max:20000|mimes:jpeg,jpg,png,gif,svg',
        ], [
            'image.image' => __('validation.image', ['attribute' => __('header.image')]),
            'image.max' => __('validation.max.file', ['attribute' => __('header.image'), 'max' => 20000 . "MB"]),
            'image.mimes' => __('validation.mimes', ['attribute' => __('header.image'), 'values' => 'jpeg,jpg,png,gif,svg']),
        ]);
        $old_image = auth()->user()->user_details->image;
        if ($old_image != null) {
            Storage::delete('public/users/' . $old_image);
        }
        $imageName = time() . '-' . uniqid() . '-' . uniqid() . '.' . $this->image->GetClientOriginalExtension();
        $this->image->storeAs('public/users', $imageName);
        auth()->user()->user_details->update([
            'image' => $imageName,
        ]);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.updated')]);
        $this->dispatchBrowserEvent('UpdateProfile', ['image' => auth()->user()->image(auth()->user()->user_details->image)]);
        $this->image = null;
        $this->done();
    }
    public function deleteImage($Image)
    {
        if ($Image != null) {
            Storage::delete('public/users/' . $Image);
            auth()->user()->user_details->update([
                'image' => null,
            ]);
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.deleted')]);
            $this->dispatchBrowserEvent('UpdateProfile', ['image' => auth()->user()->image(auth()->user()->user_details->image)]);
        } else {
            $this->dispatchBrowserEvent('message', ['type' => 'warning', 'message' => __('header.no_image')]);
        }
        $this->done();
    }
    public function done($action = true)
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->resetValidation();
        if ($action) {
            event(new UserPage());
        }
    }
    public function edit()
    {
        $this->name = auth()->user()->name;
        $this->username = auth()->user()->username;
        $this->email = auth()->user()->email;
        $this->phone = auth()->user()->phone;
        $this->address = auth()->user()->user_details->address;
    }
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->user()->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => __('validation.required', ['attribute' => __('header.name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('header.name'), 'max' => 255]),
            'username.required' => __('validation.required', ['attribute' => __('header.username')]),
            'username.string' => __('validation.string', ['attribute' => __('header.username')]),
            'username.max' => __('validation.max.string', ['attribute' => __('header.username'), 'max' => 255]),
            'username.unique' => __('validation.unique', ['attribute' => __('header.username')]),
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.string' => __('validation.string', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
            'email.max' => __('validation.max.string', ['attribute' => __('header.email'), 'max' => 255]),
            'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
            'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
            'phone.string' => __('validation.string', ['attribute' => __('header.phone')]),
            'phone.max' => __('validation.max.string', ['attribute' => __('header.phone'), 'max' => 255]),
            'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            'address.string' => __('validation.string', ['attribute' => __('header.address')]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.address'), 'max' => 255]),
        ]);
        $oldData = [
            'name : ' .  auth()->user()->name,
            'username : ' .  auth()->user()->username,
            'email : ' .  auth()->user()->email,
            'phone : ' .  auth()->user()->phone,
            'address : ' .  auth()->user()->user_details->address,
        ];
        auth()->user()->update([
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);
        auth()->user()->user_details->update([
            'address' => $this->address,
        ]);
        $newData = [
            'name : ' .  auth()->user()->name,
            'username : ' .  auth()->user()->username,
            'email : ' .  auth()->user()->email,
            'phone : ' .  auth()->user()->phone,
            'address : ' .  auth()->user()->user_details->address,
        ];
        auth()->user()->InsertToLogsTable(auth()->user()->id, 'Update', 'Profile', $oldData, $newData);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.updated')]);
        $this->dispatchBrowserEvent(
            'UpdateProfile',
            ['image' => auth()->user()->image(auth()->user()->user_details->image), 'name' => auth()->user()->name]
        );

        $this->done();
    }
    public function ChangePassword()
    {
        $this->validate([
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ], [
            'password.required' => __('validation.required', ['attribute' => __('header.password')]),
            'password.string' => __('validation.string', ['attribute' => __('header.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('header.password'), 'min' => 8]),
            'confirm_password.required' => __('validation.required', ['attribute' => __('header.confirm_password')]),
            'confirm_password.string' => __('validation.string', ['attribute' => __('header.confirm_password')]),
            'confirm_password.same' => __('validation.same', ['attribute' => __('header.confirm_password'), 'other' => __('header.password')]),
        ]);
        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.updated')]);
        $this->reset('password', 'confirm_password');
        $this->done();
    }
}
