@push('title') POS @endpush
<div>
    <div class="container-lg mt-4 {{  app()->getLocale() == 'ckb' ? 'reverse' : '' }}">
        <div wire:loading wire:target="debt,plus,minus,destroy,submit">
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
        <div class="text-center d-none invoice-seaction">
            invoice : {{ session('invoice') }}
        </div>
        <div class="row gy-3 no-print">
            <livewire:pos.barcode />
            <livewire:pos.name />
        </div>
        <div class="col-12" id="POS-Table">
            <span class="header-table d-none">
                {{ $settings->name }}
            </span>
            <span class="header-table d-none">
                {{ __('header.phone') }} : {{ $settings->phone }}
            </span>
            <span class="header-table d-none">
                {{ __('header.address') }} : {{ $settings->address }}
            </span>
            <div class="table-responsive mt-3 overflow-y-hidden pos-table">
                <table class="table table-vcenter table-nowrap print">
                    <thead>
                        <tr>
                            @if ( $sales && $sales->sale_details->count() > 0)
                            <th class="no-print"></th>
                            @endif
                            <th class="fs-4 print ">{{ __('header.name') }}</th>
                            <th class="fs-4 no-print text-center">{{ __('header.barcode') }}</th>
                            <th class="fs-4 print text-center ">{{ __('header.price') }}</th>
                            <th class="fs-4 print text-center">{{ __('header.TotalQuantity') }}</th>
                            <th class="fs-4 no-print text-center">{{ __('header.TotalPrice') }}</th>
                            <th class="fs-4 text-center no-print">{{ __('header.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sales)
                        @forelse ($sales->sale_details as $sale_detail )
                        <tr>
                            <td class="no-print">
                                <button class="btn" wire:click="destroy({{ $sale_detail->id }},{{ $sale_detail->product_id }} ,{{ $sales->id }})">
                                    <i class="fa fa-trash text-danger"></i>
                                </button>
                            </td>
                            <td class="print">
                                {{ $sale_detail->product_name }}
                            </td>
                            <td class="no-print text-center">
                                {{ $sale_detail->product_barcode }}
                            </td>
                            <td class="print text-center">
                                {{ number_format($sale_detail->product_price , 0) }} {{ __('header.currency') }}
                            </td>
                            <td class="print text-center">
                                {{ $sale_detail->quantity }}
                            </td>
                            <td class="no-print text-center">
                                {{ number_format($sale_detail->quantity *  $sale_detail->product_price , 0) }} {{ __('header.currency') }}
                            </td>
                            <td class="col-1 text-center no-print">
                                <button class="btn " wire:click.prevent="plus({{ $sale_detail->id }},{{ $sale_detail->product_id }} ,{{ $sales->id }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn " wire:click.prevent="minus({{ $sale_detail->id }},{{ $sale_detail->product_id }} ,{{ $sales->id }})">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-print">
                            <td colspan="6" class="text-center">
                                <h4>
                                    {{ __('header.NoData') }}
                                </h4>
                            </td>
                        </tr>
                        @endforelse
                        @else
                        <tr class="no-print">
                            <td colspan="6" class="text-center">
                                <h4>
                                    {{ __('header.NoData') }}
                                </h4>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <div class="col-md-6 col-12 mt-4 ">
                    <button class="btn btn-success no-print" onclick="printDiv()">
                        <i class="fas fa-print"></i>
                    </button>
                    <div class="mt-3">
                        <div class="col-lg-6 col-12 Tabel-Fotter">
                            <p class="w-100">
                                {{ __('header.PaymnetMethod') }} :
                                <span class=" mx-1">
                                    @if ($debt)
                                    {{ __('header.debt') }}
                                    @else
                                    {{ __('header.Cash') }}
                                    @endif
                                </span>
                            </p>
                            <p class="fw-bolder">
                                {{ __('header.TotalQuantity') }} :
                                <span class="fs-3 mx-1">
                                    @if ($sales)
                                    {{ $sales->sale_details->sum('quantity') }}
                                    @else
                                    0
                                    @endif
                                </span>
                            </p>
                            <p class="fw-bolder">
                                {{ __('header.TotalPrice') }} :
                                <span class="fs-3 mx-1">
                                    @if ($sales)
                                    {{ number_format($sales->total,0) }} {{ __('header.currency') }}
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
                </div>
                <div class="row mb-5 ">
                    <div class="col-md-6 col-12 mt-4 no-print ">
                        <div class="d-flex justify-content-between aligin-items-center">
                            <p class="d-flex align-items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation fs-2"></i>
                                <span class="fw-bold">
                                    {{ __('header.debt seaction') }}
                                </span>
                            </p>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="debt">
                                <label class="form-check-label">
                                    {{ __('header.debt') }}
                                </label>
                            </div>
                        </div>
                        @if ($debt)
                        <div class="col-12">
                            <div class="my-2">
                                <label for="" class="form-label">{{ __('header.name') }}</label>
                                <input type="text" class="form-control" wire:model.defer="name">
                                @error('name') <span class="text-danger"> {{ $message }}</span> @enderror
                            </div>
                            <div class="my-2">
                                <label for="" class="form-label">{{ __('header.phone') }}</label>
                                <input type="tel" class="form-control" wire:model.defer="phone">
                                @error('phone') <span class="text-danger"> {{ $message }}</span> @enderror
                            </div>
                            <div class="my-2">
                                <label for="" class="form-label">{{ __('header.currentPaid') }}</label>
                                <input type="number" class="form-control price" value="0" placeholder="0" wire:model.defer="currentpaid">
                                @error('currentpaid') <span class="text-danger"> {{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif
                        <div class="my-2">
                            <label for="" class="form-label">{{ __('header.discount') }}</label>
                            <input type="number" class="form-control price" value="0" placeholder="0" wire:model.defer="discount">
                            @error('discount') <span class="text-danger"> {{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 mb-5 mt-3">
                        <div class="no-print">
                            <button class="btn btn-cyan px-5 " wire:click.prevent="submit">
                                {{ __('header.sale') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>