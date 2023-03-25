<x-modal.add target="add-update" title="{{ __('header.add_' , ['name'=>__('header.Product')]) }}" modalWidth="modal-lg" wire="">
    <div wire:loading wire:target="add,updateProduct">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="add,updateProduct">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ $UpdateProduct ?__('header.update_' , ['name'=>''])  : __('header.add_', ['name'=>''])  }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>

        <form class="position-relative" wire:submit.prevent="submit">
            @if ($expired)
            <div class="alert alert-danger" role="alert">
                <div class="d-flex">
                    <div>
                        <i class="fa fa-warning mx-2 fs-2" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="alert-title ">
                            {{ __('header.warning') }}
                        </h4>
                        <div class="">
                            {{ __('header.porduct_is_expire') }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row g-4">
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.purches_price') }}</label>
                    <input type="text" class="form-control" wire:model.defer="purches_price" placeholder="{{ __('header.enter_',['name'=> __('header.purches_price')]) }}" />
                    @error('purches_price')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.sale_price') }}</label>
                    <input type="text" class="form-control" wire:model.defer="sale_price" placeholder="{{ __('header.enter_',['name'=> __('header.sale_price') ]) }}" />
                    @error('sale_price')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.quantity') }}</label>
                    <input type="text" class="form-control" wire:model.defer="quantity" placeholder="{{ __('header.enter_',['name'=> __('header.quantity')]) }}" />
                    @error('quantity')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.expire_date') }}</label>
                    <input type="date" class="form-control" wire:model.defer="expire_date" />
                    @error('expire_date')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary  pt-2 px-3">
                    {{ $UpdateProduct ? __('header.update') : __('header.add+') }}
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