<x-modal.add target="update" modalWidth="modal-lg">
    <div wire:loading wire:target="edit">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="edit">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ __('header.ProfileEdit') }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click="done(false)"></button>
        </div>
        <form wire:submit.prevent="submit">
            <div class="row g-3">
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('header.name') }}">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.username') }}</label>
                    <input type="text" class="form-control" wire:model.defer="username" placeholder="{{ __('header.username') }}">
                    @error('username')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.phone') }}</label>
                    <input type="tel" class="form-control" wire:model.defer="phone" placeholder="{{ __('header.phone') }}">
                    @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.email') }}</label>
                    <input type="email" class="form-control" wire:model.defer="email" placeholder="{{ __('header.email') }}">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.address') }}</label>
                    <input type="text" class="form-control" wire:model.defer="address" placeholder="{{ __('header.address') }}">
                    @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary  px-3">
                        {{ __('header.update')}}
                    </button>
                    <span class="animated-dots mx-3 fs-3" wire:loading wire:target="submit">
                        {{ __('header.waiting') }}
                    </span>
                </div>
            </div>
        </form>
    </div>
</x-modal.add>
