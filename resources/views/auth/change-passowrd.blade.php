@push('title') Change Password @endpush
<div>
    <div class="bg-white page page-center {{ app()->getLocale() == 'ckb' ? 'reverse' : '' }}">
        <div class="col-lg-3  col-md-8 col-12 m-auto shadow-sm  py-4 px-3 rounded-2 bg-white">
            <form wire:submit.prevent="ChangePassword">
                <div class="row g-3">
                    <div class="mb-2">
                        <label class="form-label">
                            {{ __('header.password') }}
                        </label>
                        <div class="d-flex  align-items-center justify-content-center gap-2">
                            <input type="password" class="form-control" id="password" placeholder="{{ __('header.enter_' ,['name'=> __('header.password')]) }}" wire:model.defer="password">
                            <span class="">
                                <i class="fa fa-eye-slash cursor-pointer" onclick="seePassword('password', this)"></i>
                            </span>
                        </div>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            {{ __('header.confirm_password') }}
                        </label>
                        <div class="d-flex  align-items-center justify-content-center gap-2">
                            <input type="password" class="form-control" id="confirm_password" placeholder="{{ __('header.enter_' ,['name'=> __('header.confirm_password')]) }}" wire:model.defer="confirm_password">
                            <span class="">
                                <i class="fa fa-eye-slash cursor-pointer" onclick="seePassword('confirm_password', this)"></i>
                            </span>
                        </div>
                        @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-3">
                            {{ __('header.Change Password')}}
                        </button>
                        <div wire:loading wire:target="ChangePassword">
                            {{ __('header.waiting') }}
                            <span class="animated-dots mx-3 fs-3" wire:loading wire:target="ChangePassword">
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>