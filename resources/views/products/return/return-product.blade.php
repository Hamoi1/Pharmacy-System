@push('title') Return Product @endpush
<div>
    <div wire:loading wire:target="search">
        <div class="loading">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}" width="150px" alt="">
                </div>
                <h1 class="loading-title ">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3">
        <div>
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="fw-bolder fs-1">
                            {{ __('header.Return Product') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="edit,add">
            <div class="d-flex justify-content-center">
                <h3>
                    {{ __('header.waiting') }}
                    <span class="animated-dots "></span>
                </h3>
            </div>
        </div>
        <div wire:loading.remove wire:target="edit,add">
            <div class="row g-3">
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 position-relative">
                    <label for="">{{ __('header.barcodeOrname') }}</label>
                    <input type="text" class="form-control" value="{{ $barcodeOrname ?? '' }}" wire:model.debounce.20ms="barcodeOrname" placeholder="{{ __('header.barcodeOrname') }}" autocomplete="off">
                    @error('barcodeOrname')<span class="text-danger">{{ $message }}</span>@enderror
                    @if ($products != null)
                    <div class="border p-2  mt-1 rounded autocomplete">
                        <span class="dropdown-header">{{ __('header.Products') }}</span>
                        @forelse ($products as $p )
                        <div class="dropdown-item autocomplete-data" wire:click="$set('barcodeOrname','{{ $p->name }}')">
                            {{ $p->name }}
                        </div>
                        @empty
                        <div class="dropdown-item">
                            {{ __('header.NoData') }}
                        </div>
                        @endforelse
                    </div>
                    @endif
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 position-relative">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <label class="form-label mt-1"> {{ __('header.Customers') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model.debounce.1ms="CustomerSearch">
                    </div>
                    <select wire:model="customer" class="form-select ">
                        <option value="">{{ __('header.Customer') }}</option>
                        @foreach ($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('customer') <span class="text-danger"> {{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-dark" wire:click.prevent="search()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-12 shadow-sm rounded p-3">
                    @if ($CustomerSales)
                    <div class="table-responsive mb-3 d-print-none" wire:loading.remove wire:target="search">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                                <tr>
                                    <th class="col-1 fs-4">
                                        Invoice-ID
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.TotalPrice') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.product_name') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.quantity') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.discount') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.status') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.TotalPriceDebt') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.currentPaid') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.debtPrice') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.date') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($CustomerSales as $sale )
                                <tr wire:click="$set('SaleId','{{ $sale->id }}')" @class(['bg-dark'=>$SaleId == $sale->id,'cursor-pointer'])>
                                    <td>
                                        {{ $sale->invoice }}
                                    </td>
                                    <td>
                                        {{ number_format($sale->total,0,',',',') .' '. __('header.currency') }}
                                    </td>
                                    <td>
                                        {{ $sale->sale_details[0]->products->name }}
                                    </td>
                                    <td>
                                        {{ $sale->sale_details[0]->quantity }}
                                    </td>
                                    <td>
                                        {{ $sale->discount != null ?  number_format($sale->discount,0) .' '. __('header.currency') : __('header.Not-discount') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $sale->paid ? 'success' : 'info' }}">
                                            {{ $sale->paid ? __('header.Cash') : __('header.debt') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $sale->debt_sale  ? number_format($sale->debt_sale->amount,0,',',',') .' '. __('header.currency') : '' }}
                                    </td>
                                    <td>
                                        {{ $sale->debt_sale  ? number_format($sale->debt_sale->paid,0,',',',') .' '. __('header.currency') : '' }}
                                    </td>
                                    <td>
                                        {{ $sale->debt_sale  ? number_format($sale->debt_sale->remain,0,',',',') .' '. __('header.currency') : '' }}
                                    </td>
                                    <td>
                                        {{ $sale->created_at }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <h4>
                                            {{ __('header.NoData') }}
                                        </h4>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            @error('selectSale') <span class="text-danger"> {{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-2 col-12">
                            <label for="">{{ __('header.returnQuantity') }}</label>
                            <input type="text" class="form-control" wire:model.defer="quantity" placeholder="{{ __('header.returnQuantity') }}">
                            @error('quantity')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary pt-2 px-3" wire:click="ReturnProducts">
                                {{ __('header.Return Product') }}
                            </button>
                            <div wire:loading wire:target="ReturnProducts">
                                <span class="animated-dots mx-2 fs-3">
                                    {{ __('header.waiting') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>