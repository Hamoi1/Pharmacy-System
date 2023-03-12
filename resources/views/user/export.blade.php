<x-modal.add target="export" modalWidth="modal-lg">
    <div wire:loading wire:target="export">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="export">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ __('header.Export')}}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>
        <form wire:submit.prevent="ExportData">
            <div class="form-group">
                <div class="d-flex align-items-center flex-wrap gap-3 p-2 mt-3 not-reverse">
                    @foreach($ExportData as $key => $value)
                    <div class="mt-1">
                        <div class="form-check form-switch d-flex align-items-center justify-content-center gap-1">
                            <input class="form-check-input input-export" wire:loading.attr="disabled" aria-checked="false" type="checkbox" wire:click="Upload('{{ $key }}')" value="{{ $key }}">
                            <label class="form-check-label mt-2">{{ $value }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-2 col-12">
                    <label for="">
                        {{ __('header.exportquantity') }}
                    </label>
                    <input type="number" class="form-control" wire:model="quantity" id="name" placeholder="{{ __('header.exportquantity') }}">
                    @error('quantity')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary  px-3 pt-2">
                    {{ __('header.Export') }}
                </button>
                <div wire:loading wire:target="ExportData">
                    {{ __('header.waiting') }}
                    <span class="animated-dots fs-3">
                    </span>
                </div>
            </div>
        </form>
    </div>
</x-modal.add>