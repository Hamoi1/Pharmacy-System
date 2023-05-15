<x-modal.view target="view" title="{{ __('header.SaleInformation') }}" modalWidth="modal" wire="">
    <div wire:loading wire:target="View">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="View">
        <div class="d-print-block">
            @if ($saleView)
            <div class="row g-3">
                <div class="shadow-sm  p-2 rounded mx-2 text-center my-3">
                    <span class="fw-bold">Invoice : </span>
                    <span>{{ $saleView->invoice }}</span>
                </div>
                <div class="col-lg-7 col-12">
                    <span class="fw-bold">{{ __('header.salesBy') }} : </span>
                    <span>{{ $saleView->user_name ?? __('header.userIsDeleted') }}</span>
                </div>
                <div class="col-lg-5 col-12">
                    <span class="fw-bold">{{ __('header.date') }} : </span>
                    <span class="not-reverse">{{ $saleView->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="col-lg-6 col-12">
                    <span class="fw-bold">{{ __('header.TotalPrice') }} : </span>
                    <span class="mx-2">
                        {{ number_format($saleView->total,2,',',',')  }} {{ __('header.dolar')}}
                        {{ $ConvertDolarToDinar($saleView->total) }} {{ __('header.dinar') }}
                    </span>
                </div>
                <div class="col-lg-6 col-12">
                    <span class="fw-bold">{{ __('header.TotalQuantity') }} : </span>
                    <span>
                        {{ number_format($saleView->sale_details->sum('quantity'),0) }}
                    </span>
                </div>
                <div class="col-lg-6 col-12">
                    <span class="fw-bold">{{ __('header.discount') }} : </span>
                    @if ($saleView->discount != 0)
                    <span class="mx-2">
                        {{ number_format($saleView->discount,2,',',',') .' '. __('header.dolar') }}
                    </span>
                    {{ $ConvertDolarToDinar($saleView->discount) }} {{ __('header.dinar') }}
                    @else
                    <span class="mx-2">
                        {{ __('header.Not-discount')}}
                    </span>
                    @endif
                </div>
                <div class="col-lg-6 col-12">
                    <span class="fw-bold">{{ __('header.PaymnetMethod') }} : </span>
                    <span>
                        {{ $saleView->debt != null ? __('header.debt') : __('header.Cash') }}
                    </span>
                </div>
                <div class="hr-text mb-2 mt-3"></div>
                <div class="col-12">
                    <span class="fw-bold">{{ __('header.Customer') }} : </span>
                    <span>
                        {{ $saleView->customer_name ?? __('header.customerIsDeleted') }}
                    </span>
                </div>
                <div class="col-12 ">
                    <p>
                        {{ __('header.Products') }}
                    </p>
                    <div class="table-responsive mt-3">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                                <tr>
                                    <th class="col-1 fs-4">
                                        {{ __('header.name') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.price') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.quantity') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($saleView->sale_details as $sale_detail )
                                <tr>
                                    <td>
                                        {{ $sale_detail->products->name }}
                                    </td>
                                    <td>
                                        {{ number_format($sale_detail->products->sale_price, 2,',',',') }} {{ __('header.dolar') }}
                                    </td>
                                    <td>
                                        {{ number_format($sale_detail->quantity, 0,',',',') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <h4>
                                            {{ __('header.NoData') }}
                                        </h4>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-modal.view>