<?php

namespace App\Http\Controllers\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Login extends Component
{
    public function render()
    {
        return view('auth.login');
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function rules()
    {
        return [
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|exists:users,email',
            'password' => 'required|min:8'
        ];
    }
    public function messages()
    {
        return [
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
            'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
            'email.exists' => __('validation.exists', ['attribute' => __('header.email')]),
            'password.required' => __('validation.required', ['attribute' => __('header.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('header.password'), 'min' => 8]),
        ];
    }
    public $email, $password;
    public function login()
    {
        $this->validate($this->rules(), $this->messages());
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            if (auth()->user()->status === 1) {
                $this->reset();
                $this->resetErrorBag();
                $this->resetValidation();
                return redirect()->route('dashboard', app()->getLocale());
            } else {
                auth()->logout();
                notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addError(__('header.Account_not_active'));
            }
        } else {
            $this->addError('email', __('header.failed'));
        }
    }
}
