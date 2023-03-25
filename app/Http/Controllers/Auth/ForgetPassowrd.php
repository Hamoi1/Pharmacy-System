<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ForgetPassowrd extends Component
{
    public $email, $VerifyCode, $code;
    protected $rules = [
        'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|exists:users,email',
    ];
    public function mount()
    {
        Cache::get('email') != null ? $this->VerifyCode = true : $this->VerifyCode = false;
    }
    private function Message()
    {
        return [
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
            'email.exists' => __('validation.exists', ['attribute' => __('header.email')]),
            'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
            'code.required' => __('validation.required', ['attribute' => __('header.code')]),
            'code.exists' => __('validation.exists', ['attribute' => __('header.code')]),
            'code.min' => __('validation.min.string', ['attribute' => __('header.code'), 'min' => 8]),
            'code.max' => __('validation.max.string', ['attribute' => __('header.code'), 'max' => 8]),
        ];
    }

    public function render()
    {
        return view('auth.forget-passowrd');
    }
    public function send()
    {
        $this->validate($this->rules, $this->Message());
        $this->resetValidation('email');
        $user = User::where('email', $this->email)->first();
        $token = Str::random(100) . '-' . time() . '-' . Str::random(100);
        // create random code with length 8
        $code = rand(1, 100000) . Str::random(1) . rand(1, 1000000);
        $code = substr($code, 0, 8);
        $passwrodReset =  DB::table('password_resets')->select('email')->where('email', $this->email)->first() == null
            ? DB::table('password_resets')->insert([
                'email' => $this->email,
                'token' => $token,
                'code' => $code,
                'created_at' => now(),
            ])
            : DB::table('password_resets')->where('email', $this->email)->update([
                'token' => $token,
                'code' => $code,
                'created_at' => now(),
            ]);
        $user->notify(new \App\Notifications\ForgotPassword($user, $code));
        Cache::put('email', $this->email, now()->addMinutes(5));
        return redirect()->route('forget-password', app()->getLocale());
    }
    public function check()
    {
        $this->validate([
            'code' => 'required|exists:password_resets,code|min:8|max:8',
        ], $this->Message());
        $Check = DB::table('password_resets')->where('code', $this->code)->first();
        Cache::forget('email');
        return redirect()->route('ChangePassowrd', [
            'lang' => app()->getLocale(),
            'token'  => $Check->token,
            'email' => $Check->email,
        ]);
    }
    public function resend()
    {
        $user = User::where('email', Cache::get('email'))->first();
        $code  = DB::table('password_resets')->select('code')->where('email', Cache::get('email'))->first();
        $user->notify(new \App\Notifications\ForgotPassword($user, $code->code));
        flash()->addSuccess(__('header.resendMessage'));
        $this->mount();
    }
}
