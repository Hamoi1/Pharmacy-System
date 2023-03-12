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
                {{ $UpdateProduct ?__('header.update_' , ['name'=> __('header.Product')])  : __('header.add_', ['name'=> __('header.Product')])  }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>

        <form class="position-relative" wire:submit.prevent="submit">
            @if ($expire)
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
                    <label>{{ __('header.product_name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('header.enter_',['name'=>__('header.product_name')]) }}" />
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.barcode') }}</label>
                    <input type="text" id="barcode" wire:model.defer="barcode" id="barcode" class="form-control" placeholder="{{ __('header.enter_',['name'=> __('header.barcode')])  }}" />
                    @error('barcode')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.category_product') }}</label>
                    <select class="form-select" wire:model="category_id">
                        <option value="">{{ __('header.category_product') }}</option>
                        @forelse ($categorys as $category )
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @empty
                        <option value="">{{ __('header.NoData') }}</option>
                        @endforelse
                    </select>
                    @error('category_id')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label>{{ __('header.Suppliers') }}</label>
                    <select class="form-select" wire:model="supplier_id">
                        <option value="">{{ __('header.Suppliers') }}</option>
                        @forelse ($suppliers as $supplier )
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @empty
                        <option value="">{{ __('header.NoData') }}</option>
                        @endforelse
                    </select>
                    @error('supplier_id')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
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
                @if (!$UpdateProduct)
                <div class="col-12">
                    <p>
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ __('header.choose_image') }}
                    </p>
                    <label>{{ __('header.image') }}</label>
                    <input type="file" class="form-control" multiple wire:model="images" accept="image/*" />
                    <div class="mt-3" wire:loading wire:target="images">
                        <span>{{ __('header.uploading') }}</span>
                        <span class="spinner-border mx-2 text-primary fs-5" role="status"></span>
                    </div>
                    @error('images.*')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                @if($images)
                <div class="col-12">
                    <div class="row text-center g-lg-2 gy-3">
                        @foreach ($images as $image)
                        @if (in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'svg']))
                        <div class="col-lg-3 col-md-6 col-12 position-relative">
                            <img src="{{ $image->temporaryUrl() }}" width="300px" height="300px" class="img-fluid rounded object-cover">
                            <span class="position-absolute top-0 left-0 delete-img" wire:click.prevent="removeImage({{ $loop->index }})">
                                <i class="fa fa-times"></i>
                            </span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
                <div class="col-12">
                    <label>{{ __('header.about product') }}</label>
                    <textarea class="form-control" wire:model.defer="description" placeholder="{{ __('header.enter_' , ['name'=>__('header.about product')]) }}" rows="4"></textarea>
                    @error('description')<span class="text-danger">{{ $message }}</span>@enderror
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