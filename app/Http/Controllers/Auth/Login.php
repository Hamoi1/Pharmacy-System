<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserActions;
use Illuminate\Routing\Route;
use Livewire\Component;

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
                $data = auth()->user()->name . ' Login to System ';
                auth()->user()->InsertToLogsTable(auth()->user()->id, 'Login', 'Login', $data, $data);
                return redirect()->route('dashboard', app()->getLocale());
            } else {
                auth()->logout();
                $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.Account_not_active')]);
            }
        } else {
            $this->addError('email', __('header.failed'));
        }
    }
}
