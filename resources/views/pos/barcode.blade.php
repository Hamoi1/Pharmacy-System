<div class="col-lg-6 col-md-6 col-12">
    <label class="form-label">
        {{ __('header.barcode') }}
        <i class="fas fa-barcode"></i>
    </label>
    <input type="text" id="barcode" wire:model.debounce.10ms="barcode" class="form-control" placeholder="{{ __('header.barcode') }}" autocomplete="off">
    @error('barcode') <span class="text-danger"> {{ $message }}</span> @enderror
</div>