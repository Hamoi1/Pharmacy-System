<x-modal.add target="generate" modalWidth="modal">
    <div wire:loading wire:target="GenerateBarcode">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="GenerateBarcode">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ __('header.barcodes.generate') }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>
        <form wire:submit.prevent="submit">
            <div class="row">

                <div class="mb-3">
                    <label class="form-label">
                        {{ __('header.barcodes.index') }}
                    </label>
                    <div class="d-flex align-items-center gap-2 ">
                        <div class="col">
                            <input type="text" class="form-control" wire:model.defer="barcode">
                        </div>
                        <div class="col-auto">
                            <a href="" class="btn btn-icon " aria-label="Button" wire:click.prevent="GenerateBarcode">
                                <i class="fa-solid fa-arrow-rotate-right"></i>
                            </a>
                        </div>
                    </div>
                    @error('barcode') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3 col-lg-6 col-12">
                    <label class="form-label">
                        {{ __('header.name') }}
                    </label>
                    <input type="text" class="form-control" wire:model.defer="barcode_name">
                    @error('barcode_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3 col-lg-6 col-12">
                    <label class="form-label">
                        {{ __('header.barcodes.quantity') }}
                    </label>
                    <input type="text" class="form-control" wire:model.defer="quantity">
                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @if($barcode !=null )
                <div class="w-auto">
                    <div class="text-center ">
                        {!! DNS1D::getBarcodeHTML($barcode, 'I25') !!}
                        <p>{{ $barcode }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary px-3">
                    {{ __('header.add+') }}
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