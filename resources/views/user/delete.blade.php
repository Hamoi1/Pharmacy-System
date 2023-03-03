<x-modal.delete target="delete" title="{{ __('header.title_delete' , ['name'=>__('header.User')]) }}" modalWidth="modal" wire="wire:click=done">
    <div wire:loading>
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove>
        <span>
            {{__('header.AreYouSure' , ['name'=>__('header.User')]) }}
        </span>
        <form>
            <div class="row g-3">
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-danger px-3 py-1 mx-2" wire:click.prevent="delete({{ $UserId }})">
                        {{ __('header.delete') }}
                    </button>
                    <button class="btn btn-primary  px-3 py-1 mx-2" wire:click.prevent="done">
                        {{ __('header.cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-modal.delete>