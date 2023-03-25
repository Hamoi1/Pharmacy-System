@push('title') Print Sale @endpush
<div>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} container-lg px-2 px-lg-5 mt-3 pt-3">
        <p class="fw-bolder fs-5 my-2 not-reverse d-none header-table">
            Invoice : {{ $sale->invoice }}
        </p>
        <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'text-end' : 'text-start' }} col-12" id="POS-Table">
            <span class="header-table d-none">
                {{ __('header.pharmacyName') }} : {{ $settings->name }}
            </span>
            <span class="header-table d-none">
                {{ __('header.phone') }} : {{ $settings->phone }}
            </span>
            <span class="header-table d-none">
                {{ __('header.address') }} : {{ $settings->address }}
            </span>
            <div class="table-responsive mt-3 overflow-y-hidden ">
                <table class="table table-vcenter table-nowrap print">
                    <thead>
                        <tr>
                            <th class="fs-4 print">{{ __('header.name') }}</th>
                            <th class="fs-4  d-print-none text-center">{{ __('header.barcode') }}</th>
                            <th class="fs-4 print text-center">{{ __('header.price') }}</th>
                            <th class="fs-4 print text-center">{{ __('header.TotalQuantity') }}</th>
                            <th class="fs-4  d-print-none text-center">{{ __('header.TotalPrice') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sale->sale_details as $sale_detail )
                        <tr>
                            <td class="print">
                                {{ $sale_detail->product_name }}
                            </td>
                            <td class=" d-print-none text-center">
                                {{ $sale_detail->product_barcode }}
                            </td>
                            <td class="print text-center">
                                {{ number_format($sale_detail->product_price,0) }} {{ __('header.currency') }}
                            </td>
                            <td class="print text-center">
                                {{ $sale_detail->quantity }}
                            </td>
                            <td class=" d-print-none text-center">
                                {{ number_format($sale_detail->quantity *  $sale_detail->product_price, 0) }} {{ __('header.currency') }}
                            </td>
                        </tr>
                        @empty
                        <tr class=" d-print-none">
                            <td colspan="6" class="text-center">
                                <h4>
                                    {{ __('header.NoData') }}
                                </h4>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                @if ($sale->debt_sale)
                <div class="d-flex align-items-center flex-wrap gap-lg-3  gap-2 my-3">
                    <div class="col-lg-3 mx-lg-1 mx-2">
                        {{ __('header.name') }} : {{ $sale->debt_sale->name }}
                    </div>
                    <div class="col-lg-3 mx-lg-1 mx-2">
                        {{ __('header.phone') }} : {{ $sale->debt_sale->phone }}
                    </div>
                    <div class="col-lg-3 mx-lg-1 mx-2">
                        {{ __('header.debtPaid') }} : {{ number_format($sale->debt_sale->amount , 0) }} {{ __('header.currency') }}
                    </div>
                    <div class="col-lg-3 mx-lg-1 mx-2">
                        {{ __('header.currentPaid') }} : {{ number_format($sale->debt_sale->paid, 0) }} {{ __('header.currency') }}
                    </div>
                    <div class="col-lg-3 mx-lg-1 mx-2">
                        {{ __('header.debtPrice') }} : {{ number_format($sale->debt_sale->remain, 0) }} {{ __('header.currency') }}
                    </div>
                </div>
                <hr class=" d-print-none">
                @endif
                <div class="col-lg-6 col-12 Tabel-Fotter ">
                    <p class="w-100">
                        {{ __('header.PaymnetMethod') }} :
                        <span class=" mx-1">
                            @if ($sale->paid)
                            {{ __('header.Cash') }}
                            @else
                            {{ __('header.debt') }}
                            @endif
                        </span>
                    </p>
                    <p class="fw-bolder">
                        {{ __('header.TotalQuantity') }} :
                        <span class="mx-1">
                            @if ($sale)
                            {{ $sale->sale_details->sum('quantity') }}
                            @else
                            0
                            @endif
                        </span>
                    </p>
                    <p class="fw-bolder {{ $sale->discount ?? ' d-print-none' }}">
                        {{ __('header.discount') }} : {{ $sale->discount  ? number_format($sale->discount, 0) .' '. __('header.currency') : __('header.Not-discount') }}
                    </p>
                    <p class="fw-bolder">
                        {{ __('header.TotalPrice') }} :
                        <span class="mx-1">
                            @if ($sale)
                            {{ number_format($sale->total,0) }} {{ __('header.currency') }}
                            @else
                            0 {{ __('header.currency') }}
                            @endif
                        </span>
                    </p>
                    <p class="d-none date">
                        {{ __('header.date') }} : {{ now()->format('Y-m-d') }}
                    </p>
                </div>
            </div>
            <div class="col-12 mt-2 my-3  d-print-none">
                <button class="btn btn-success" onclick="printDiv()">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </div>
</div>