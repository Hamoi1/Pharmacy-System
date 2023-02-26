<div class=" col-lg-6 col-md-6 col-12">
    <form wire:submit.prevent="add">
        <div class="row position-relative">
            <div class="col-10">
                <label class="form-label  name-label">
                    {{ __('header.product_name') }}
                    <i class=" fas fa-box"></i>
                </label>
                <input type="text" wire:model.defer="name" class="form-control rounded " placeholder="{{ __('header.product_name') }}">
            </div>
            <div class="col-2" style="position: absolute;top:50%;{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'left:0' : 'right:0' }}; translate:translateX(50%);">
                <button class="btn btn-primary " type="submit">
                    <i class=" fas fa-plus"></i>
                </button>
            </div>
        </div>
        @error('name') <span class="text-danger"> {{ $message }}</span> @enderror
    </form>
</div>