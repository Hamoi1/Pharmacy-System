@push('title') Login @endpush
<div class=" page page-center {{ app()->getLocale() == 'ckb' || app()->getLocale() == 'ar' ? 'reverse' : '' }}">
    <div class="">
        <div class="row g-0">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
            <div class="col-md-8 col-lg-6">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-lg-8 mx-auto">
                                <p class="my-1 mb-4 fs-2 text-center">
                                    Welcome back to <span class="fw-bolder mx-2 ">{{ $settings->name }}</span>
                                </p>
                                <form class="" wire:submit.prevent="login">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            {{ __('header.email') }}
                                        </label>
                                        <input type="email" class="form-control" placeholder="{{ __('header.enter_' ,['name'=> __('header.email')]) }}" wire:model.defer="email" value="{{ $email }}">
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
                                        <button type="submit" class="btn btn-primary  btn-primary   w-100">
                                            {{ __('header.login') }}
                                        </button>
                                        <div wire:loading wire:target="login" class="mt-2">
                                            {{ __('header.waiting') }}
                                            <span class="animated-dots mx-2 fs-3">
                                            </span>
                                        </div>
                                    </div>
                                </form>
                                <div class=" text-center mt-3 d-flex align-items-center ">
                                    <a href="{{ route('login','en') }}" class="btn btn-sm shadow-sm rounded-2 mx-1 text-decoration-none ">
                                        English
                                    </a>
                                    <a href="{{ route('login','ar') }}" class="btn btn-sm shadow-sm rounded-2 mx-1 text-decoration-none ">
                                        Arabic
                                    </a>
                                    <a href="{{ route('login','ckb') }}" class="btn btn-sm shadow-sm rounded-2 mx-1 text-decoration-none ">
                                        Kurdish
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>