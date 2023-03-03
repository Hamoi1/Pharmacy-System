<x-modal.add target="ChangePassword" modalWidth="modal-lg">
    <div wire:loading>
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove>
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ __('header.ChangePassword') }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>
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
                    <button type="submit" class="btn btn-primary  px-3">
                        {{ __('header.update')}}
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
</x-modal.add>