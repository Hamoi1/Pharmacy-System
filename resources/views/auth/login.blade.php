@push('title') Login @endpush
<div>
    <div class="bg-white page page-center {{ app()->getLocale() == 'ckb' ? 'reverse' : '' }}">
        <div class="col-lg-3  col-md-8 col-12 m-auto shadow-sm  py-4 px-3 rounded-2 bg-white">
            <form class="" wire:submit.prevent="login">
                <div class="row">
                    <h2 class=" text-center my-1 mb-4">
                        Welcome back to {{ $settings->name }}
                    </h2>
                    <div class="mb-3">
                        <label class="form-label">
                            {{ __('header.email') }}
                        </label>
                        <input type="email" class="form-control" placeholder="{{ __('header.enter_' ,['name'=> __('header.email')]) }}" wire:model.defer="email">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between">
                            <span>
                                {{ __('header.password') }}
                            </span>
                            <a href="{{ route('forget-password',app()->getLocale()) }}">
                                {{ __('header.forgetPassword') }}
                            </a>
                        </label>
                        <div class="d-flex  align-items-center justify-content-center gap-2">
                            <input type="password" class="form-control" id="password" placeholder="{{ __('header.enter_' ,['name'=> __('header.password')]) }}" wire:model.defer="password">
                            <span class="">
                                <i class="fa fa-eye-slash cursor-pointer" onclick="seePassword('password', this)"></i>
                            </span>
                        </div>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row mt-3">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('header.login') }}
                        </button>
                        <div wire:loading wire:target="login" class="mt-2">
                            {{ __('header.waiting') }}
                            <span class="animated-dots mx-2 fs-3">
                            </span>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mt-3">
                <a href="{{ route(Route::currentRouteName() ,'en') }}" class="mx-1">
                    English
                </a>
                <a href="{{ route(Route::currentRouteName(),'ckb') }}" class="mx-1">
                    کوردی
                </a>
            </div>
        </div>
    </div>

</div>