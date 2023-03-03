<x-modal.add target="add-update" modalWidth="modal">
    <div wire:loading wire:target="add,update">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="add,update">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ $updateCategory ?__('header.update_' , ['name'=> __('header.Category')])  : __('header.add_', ['name'=> __('header.Category')])  }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>
        <form wire:submit.prevent="submit">
            <div class="row g-3">
                <div class=" col-12">
                    <label for="">{{ __('header.name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('header.name') }}" autofocus>
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary pt-2 px-3">
                        {{ $updateCategory ? __('header.update') : __('header.add+') }}
                    </button>
                    <div wire:loading wire:target="submit">
                        {{ __('header.waiting') }}
                        <span class="animated-dots mx-2 fs-3">
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-modal.add>