@push('title') Update Image @endpush
<div>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3">
        <div class="mt-4">
            <x-page-header title="{{ __('header.Edit_Product_image') }}" target="#add-update" wire="" />
        </div>
        <div wire:loading wire:target="deleteAll">
            <div class="loading">
                <div class="loading-content">
                    <div class="loading-icon">
                        <img src="{{ asset('assets/images/Spinner.gif') }}" width="250px" alt="">
                    </div>
                    <h1 class="loading-title ">
                        {!! __('header.waiting') !!}
                    </h1>
                </div>
            </div>
        </div>
        @include('products.Update-image.update')
        <div class="row mt-3 g-3 ">
            <div class="col-lg-2 col-md-4 col-12  py-2 text-break">
                {{ __('header.Product') }} :
                <a href="{{  route('products',['lang'=>app()->getLocale() ,'s'=>$product->name]) }}" class="mx-1">
                    {{ $product->name }}
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-12  py-2 text-break">
                {{ __('header.Category') }} :
                <span class="mx-1">
                    {{ $product->category_name ?? __('header.not have' , ['name'=>__('header.Category')]) }}
                </span>
            </div>
            <div class="col-lg-2 col-md-4 col-12  py-2 text-break">
                {{ __('header.Supplier') }} :
                <span class="mx-1">
                    {{ $product->supplier_name ?? __('header.not have' , ['name'=>__('header.Supplier')]) }}
                </span>
            </div>
            <div class="col-lg-2 col-md-4 col-12  py-2 text-break">
                {{ __('header.purches_price') }} :
                <span class="mx-1">
                    $ {{ $product->purches_price }}
                </span>
            </div>
            <div class="col-lg-2 col-md-4 col-12  py-2 text-break">
                {{ __('header.sale_price') }} :
                <span class="mx-1">
                    $ {{ $product->sale_price }}
                </span>
            </div>
            <div class="col-lg-2 col-md-4 col-12  py-2 text-break">
                {{ __('header.quantity') }} :
                <span class="mx-1">
                    {{ $product->quantity }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-12  py-2 text-break d-flex">
                {{ __('header.expire_date') }} :
                <div>
                    <span class="mx-1">
                        {{ $product->expiry_date }}
                    </span>
                    @if ($product->expiry_date <= date('Y-m-d')) <span class="badge bg-danger">
                        {{ __('header.expired') }}
                        </span>
                        @endif
                </div>
            </div>

        </div>
        @if ($product->image && $product->image != '[]')
        <div class=" my-2">
            <button class="btn btn-danger mx-2" wire:click.prevent="deleteAll">
                {{ __('header.DeleteAllImage') }}
            </button>
        </div>
        @endif
        <div class="row mt-3 mb-5 gy-4">
            @if ($product->image && $product->image != '[]')
            @foreach (json_decode($product->image) as $image )
            @if ($image != null)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 position-relative ">
                <img src="{{ asset('storage/products/'.$image) }}" class="w-100 rounded object-contain">
                <span class="position-absolute top-0 left-0 delete-img" wire:click="remove({{ $loop->index }})">
                    <i class="fa fa-times"></i>
                </span>
            </div>
            @endif
            @endforeach
            @else
            <div class="col-12">
                <p class="text-center bg-dark py-2">
                    {{ __('header.Not_have_image') }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>