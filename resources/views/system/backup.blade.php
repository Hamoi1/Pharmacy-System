<div>
    <x-modal.add target="backup" modalWidth="modal">
        <div wire:loading>
            <div class="d-flex justify-content-center">
                <h3>
                    {{ __('header.waiting') }}
                    <span class="animated-dots "></span>
                </h3>
            </div>
        </div>
        <div wire:loading.remove>
            <form wire:submit.prevent="submit">
                <div class=" col-12">
                    <label for="">{{ __('header.email') }}</label>
                    <input type="text" class="form-control" wire:model.defer="email" placeholder="{{ __('header.email') }}">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary  px-3 pt-2">
                        {{ __('header.backup') }}
                    </button>
                    <button type="button" class="btn-outline-danger btn" data-bs-dismiss="modal" aria-label="Close">
                        {{ __('header.cancel') }}
                    </button>
                    <div wire:loading wire:target="submit">
                        {{ __('header.waiting') }}
                        <span class="animated-dots fs-3">
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </x-modal.add>
    <div wire:loading wire:target="backup">
        <div class="loading">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}" width="150px" alt="">
                </div>
                <h1 class="loading-title ">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>
    <button class="btn btn-outline-dark rounded-3 py-2" data-bs-toggle="modal" data-bs-target="#backup">
        {{ __('header.backup') }}
    </button>
</div>