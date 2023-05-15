@push('title') Add Or Edit Quantity @endpush
<div>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3 ">
        <div class="row">
            <div class="col-md-3 col-12 mt-4">
                <span class="d-block mt-2">
                    {{ __('header.product_name') }} :
                    <span class="fw-bolder">
                        {{ $product->name }}
                    </span>
                </span>
                <span class="d-block mt-2">
                    {{ __('header.barcode') }} :
                    <span class="fw-bolder">
                        {{ $product->barcode }}
                    </span>
                    {!! DNS1D::getBarcodeHTML($product->barcode, 'I25') !!}
                </span>
                <span class="d-block mt-2">
                    {{ __('header.category_product') }} :
                    <span class="fw-bolder">
                        {{ $product->category_name }}
                    </span>
                </span>
                <span class="d-block mt-2">
                    {{ __('header.supplier') }} :
                    <span class="fw-bolder">
                        {{ $product->supplier_name }}
                    </span>
                </span>
            </div>
            <div class="col-md-3 col-12 mt-4">
                <span class="d-block mt-2">
                    {{ __('header.TotalQuantity').' : '.number_format($product->total_quantity,0,',',',') }}
                </span>
                <span class="d-block mt-2">
                    {{ __('header.FinalSalePrice').' : '. $product->final_sale_price }} {{ __('header.dolar') }}
                    <br>
                    {{ $ConvertDolarToDinar($product->final_sale_price) }} {{ __('header.dinar') }}
                </span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="">
                <a href="" class="btn btn-primary  btn-primary " data-bs-toggle="modal" data-bs-target="#add-update" wire:click=add>
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
        @include('products.update.add-update')
        @include('products.update.delete')
        <div class="table-responsive mt-3" wire:loading.remove wire:target="search,Category,Supplier,previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="fs-4">{{ __('header.purches_price') }}</th>
                        <th class="fs-4">{{ __('header.sale_price') }}</th>
                        <th class="fs-4">{{ __('header.quantity') }}</th>
                        <th class="fs-4 text-center">{{ __('header.expire_date') }}</th>
                        @can('Update Product')
                        <th class="col-1 fs-4 text-center">{{ __('header.actions') }}</th>
                        @endcan
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
                                {{ __('header.out_of_stock') }}
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
                        <td class=" col-1 text-center">
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#add-update" wire:click="updateProduct({{ $p->id }})">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            @can('Delete Product')
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click="$set('product_quantity_id','{{ $p->id }}')">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>