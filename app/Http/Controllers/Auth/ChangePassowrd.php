<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePassowrd extends Component
{
    public $email, $password, $confirm_password;
    public function  mount($lang, $token, $email)
    {
        $check = DB::table('password_resets')->where('email', $email)->where('token', $token)->first();
        if (!$check) {
            return redirect()->route('forget-password', app()->getLocale());
        }
        $this->$email = $email;
    }
    public function render()
    {
        return view('auth.change-passowrd');
    }
    public function ChangePassword()
    {
        $this->validate([
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ], [
            'password.required' => __('validation.required', ['attribute' => __('header.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('header.password'), 'min' => 8]),
            'confirm_password.required' => __('validation.required', ['attribute' => __('header.confirm_password')]),
            'confirm_password.same' => __('validation.same', ['attribute' => __('header.confirm_password'), 'other' => __('header.password')]),
        ]);
        $user = User::where('email', $this->email)->first();
        $user->password = Hash::make($this->password);
        $user->save();
        DB::table('password_resets')->where('email', $this->email)->delete();
        flash()->addSuccess(__('header.password_changed_successfully'));
        return redirect()->route('login', app()->getLocale());
    }
}
