<x-modal.view target="view" title="{{ __('header.title_view' , ['name'=>__('header.Product')]) }}" modalWidth="modal-lg" wire="">
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
            <span class="fw-bold mx-1">{{ __('header.FinalSalePrice') }} : </span>
            <span class="fw-normal mx-2">{{ number_format($product->final_sale_price,0,',',',')}} {{ __('header.dolar') }} </span>
            <span>{{ $ConvertDolarToDinar($product->final_sale_price) }} {{ __('header.dinar') }} </span>
        </p>
        <p>
            <span class="fw-bold mx-1">{{ __('header.TotalQuantity') }} : </span>
            <span class="fw-normal">{{ $product->total_quantity }}</span>
        </p>
        @if($product->image)
        <div class="row g-3">
            <span>
                <span class="fw-bold mx-1">{{ __('header.image') }} : </span>
            </span>
            <img src="{{ asset('storage/product/'.$product->image) }}" class="img-fluid" alt="image" width="100%" height="400" />
        </div>
        @endif
        <p class="mt-3 text-break">
            <span class="fw-bold mx-1">{{ __('header.about product') }} : </span>
            <span class="fw-normal">{{ $product->description  ?? __('header.nothing') }}</span>
        </p>
        @endif
        @if ($product)
        <div class="table-responsive mt-3" wire:loading.remove wire:target="search,Category,Supplier,previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="fs-4">{{ __('header.purches_price') }}</th>
                        <th class="fs-4">{{ __('header.sale_price') }}</th>
                        <th class="fs-4">{{ __('header.quantity') }}</th>
                        <th class="fs-4 text-center">{{ __('header.expire_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->product_quantity as $p )
                    <tr>
                        <td>
                            {{ number_format($p->purches_price,0,',',',') }} {{ __('header.dolar') }}
                        </td>
                        <td>
                            {{ number_format($p->sale_price,0,',',',') }} {{ __('header.dolar') }}
                        </td>
                        <td>
                            {{ $p->quantity }}
                            @if ($p->quantity == 0) <span class="badge bg-danger mx-2">
                            </span>
                            @endif

                        </td>
                        <td class="text-center">
                            {{ $p->expiry_date }}
                            @if ($p->expiry_date <= now()) <span class="badge bg-danger mx-2">
                                {{ __('header.expired') }}
                                </span>
                                @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-modal.view>