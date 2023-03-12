<x-modal.view target="view" title="{{ __('header.title_view' , ['name'=>__('header.Product')]) }}" modalWidth="modal" wire="wire:click=done">
    <div wire:loading wire:target="show">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="show">
        @if($product)
        <p>
            <span class="fw-bold mx-1">{{ __('header.name') }} : </span>
            <span class="fw-normal">{{ $product->name }}</span>
        </p>
        <p>
            <span class="fw-bold mx-1">{{ __('header.barcode') }} : </span>
            <span class="fw-normal">{{ $product->barcode }}</span>
        </p>
        <p>
            <span class="fw-bold mx-1">{{ __('header.Category') }} : </span>
            {{ $product->category ? $product->category_name  : __('header.not have',['name'=>__('header.Category')]) }}
        </p>
        <p>
            <span class="fw-bold mx-1">{{ __('header.supplier') }} : </span>
            {{ $product->supplier ? $product->supplier_name : __('header.not have',['name'=>__('header.supplier')]) }}
        </p>
        <p>
            <span class="fw-bold mx-1">{{ __('header.sale_price') }} : </span>
            <span class="fw-normal">{{ $product->sale_price }} {{ __('header.currency') }}</span>
        </p>
        <p>
            <span class="fw-bold mx-1">{{ __('header.quantity') }} : </span>
            <span class="fw-normal">{{ $product->quantity }}</span>
        </p>
        <!-- <p>
            <span class="fw-bold mx-1">{{ __('header.expire_date') }} : </span>
            <span class="fw-normal">{{ $product->expiry_date }}</span>
        </p> -->
        @if($product->image != null && $product->image != '[]' )
        <div class="row g-3">
            <span>
                <span class="fw-bold mx-1">{{ __('header.image') }} : </span>
            </span>
            @foreach (json_decode($product->image) as $image )
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <img src="{{ asset('storage/products/'.$image) }}" class="img-fluid rounded-3" alt="">
            </div>
            @endforeach
        </div>
        @endif
        <p class="mt-3 text-break">
            <span class="fw-bold mx-1">{{ __('header.about product') }} : </span>
            <span class="fw-normal">{{ $product->description  ?? __('header.nothing') }}</span>
        </p>
        @endif
    </div>
</x-modal.view>