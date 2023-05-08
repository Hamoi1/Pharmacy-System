<x-modal.add target="{{Route::currentRouteName() == 'sales' ? 'add-update-customer' : 'add-update'}}" modalWidth="modal-lg">
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
                {{ $CustomerUpadate ?__('header.update_' , ['name'=> __('header.Customer')])  : __('header.add_', ['name'=> __('header.Customer')])}}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="submit">
            <div class="row g-3">
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('header.name') }}">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
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
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.guarantoraddress') }}</label>
                    <input type="text" class="form-control" wire:model.defer="guarantoraddress" value="0" placeholder="{{ __('header.address') }}">
                    @error('guarantorphone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.guarantorphone') }}</label>
                    <input type="text" class="form-control" wire:model.defer="guarantorphone" value="0" placeholder="{{ __('header.address') }}">
                    @error('guarantorphone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary pt-2 px-3">
                        {{  $CustomerUpadate ?  __('header.update'): __('header.add+') }}
                    </button>
                    <div wire:loading wire:target="submit" wire:target="submit">
                        <span class="animated-dots mx-2 fs-3">
                            {{ __('header.waiting') }}
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-modal.add>