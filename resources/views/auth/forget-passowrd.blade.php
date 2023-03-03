@push('title') Forget-Password @endpush
<div>
    <div class="bg-white page page-center {{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}">
        <div class="col-lg-3  col-md-8 col-12 m-auto shadow-sm  py-4 px-3 rounded-2 bg-white">
            <form class="" wire:submit.prevent="{{ $VerifyCode?'check':'send' }}">
                <div class="row">
                    <p class="fs-3 fw-bold">
                        @if ($VerifyCode)
                        {{ __('header.CheckCode') }}
                        @else
                        {{ __('header.forgetPassword-seaction') }}
                        @endif
                    </p>
                    <div class="my-3">
                        <label class="form-label">
                            @if ($VerifyCode)
                            {{ __('header.code') }}
                            @else
                            {{ __('header.email') }}
                            @endif
                        </label>
                        <input type="{{ $VerifyCode ?'text':'email' }}" class="form-control" placeholder="{{ __('header.enter_' ,['name'=>  $VerifyCode?  __('header.code') :  __('header.email') ]) }}" wire:model.defer="{{ $VerifyCode ?'code':'email' }}">
                        @error($VerifyCode ? 'code' : 'email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row mt-3">
                        <button type="submit" class="btn btn-primary   btn-primary   w-100">
                            @if ($VerifyCode)
                            {{ __('header.check') }}
                            @else
                            {{ __('header.SendCode') }}
                            @endif
                        </button>

                        <div wire:loading wire:target="send,check" class="mt-2">
                            @if ($VerifyCode)
                            {{ __('header.waiting') }}
                            @else
                            {{ __('header.PleaseWait') }}
                            @endif
                            <span class="animated-dots mx-2 fs-3">
                            </span>
                        </div>
                    </div>
                    @if ($VerifyCode)
                    <a href="" class="mt-3 text-dark fw-bolder" wire:click.prevent="resend()">
                        {{ __('header.resend') }}
                        <i class="fa fa-rotate-left" aria-hidden="true"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>