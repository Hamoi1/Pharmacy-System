@push('title') POS @endpush
<div class="point-of-sales" wire:ignore.self>
    <div class="px-lg-5 px-3 mt-4 user-select-none {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}">
        <div wire:loading wire:target="invoice,SaleType,submit,destroy,AddNewInvoce">
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
        <div class="d-flex align-items-center justify-content-between not-reverse mb-3 border-bottom pb-2">
            <div>
                <a href="{{ route('dashboard',app()->getLocale()) }}" class="btn btn-outline-blue">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-md-4 col-10">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="col-md-8 col-10 ">
                        <div class="d-flex align-items-center gap-2">
                            <select name="" class="form-select" id="" wire:model="invoice">
                                <option value="">
                                    {{ __('Select Invoices') }}
                                </option>
                                @foreach ($invoices as $i)
                                <option value="{{ $i->invoice }}" {{ $i == $invoice ? 'selected' :'' }}>{{ $i->invoice }}</option>
                                @endforeach
                            </select>
                            <div class="addNewInvoice {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'left' : 'right' }}">
                                <button href="" class="btn btn-sm py-2" wire:click="AddNewInvoce">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        @php
                        $Type = $SaleTypeView != 'ImageView' ? 'ImageView' : 'ListView';
                        @endphp
                        <button class="btn btn-primary rounded-2 shadow" type="button" wire:click="$set('SaleTypeView','{{ $Type }}')">
                            @if ($SaleTypeView == 'ImageView')
                            <i class="fa fa-list" aria-hidden="true"></i>
                            @else
                            <i class="fa-regular fa-file-image"></i>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 ">
            @livewire('supplier.add-update')
            @livewire('customer.add-update')
            <div class="d-flex align-items-center flex-wrap gap-2 position-relative">
                @if ($SaleTypeView == 'ListView')
                <div class="col-xl-2 mt-1 col-md-4 col-sm-6 col-12 position-relative mt-3">
                    <label class="form-label barcode-label">
                        {{ __('header.barcodeOrname') }}
                    </label>
                    <input type="text" id="barcode" wire:model.debounce.1ms="data" value="{{ $data ?? '' }}" class="form-control" placeholder="{{ __('header.barcodeOrname') }}" autocomplete="off">
                    @error('data') <span class="text-danger"> {{ $message }}</span> @enderror
                    @if ($product != null)
                    <div class="border p-2  mt-1 rounded autocomplete">
                        <span class="dropdown-header">{{ __('header.Products') }}</span>
                        @forelse ($product as $p)
                        <div class="dropdown-item autocomplete-data" wire:click="AddProduct('{{ $p->id ?? ''  }}')">
                            {{ $p->name ?? '' }}
                        </div>
                        @empty
                        <div class="dropdown-item">
                            {{ __('header.NoData') }}
                        </div>
                        @endforelse
                    </div>
                    @endif
                </div>
                @endif
                <div class="col-xl-2 mt-1 col-lg-4 col-md-4 col-sm-6 col-12 ">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <label class="form-label mt-1"> {{ __('header.Suppliers') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="SupplierSearch">
                    </div>
                    <div class="input-group input-group-flat not-reverse ">
                        <select wire:model="supplier" class="form-select ">
                            <option value="">{{ __('header.Supplier') }}</option>
                            @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text p-1">
                            <a href="#" class="link-secondary mt-1 px-2" data-bs-toggle="modal" data-bs-target="#add-update-supplier">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                        </span>
                    </div>
                    @error('supplier') <span class="text-danger"> {{ $message }}</span> @enderror
                </div>
                <div class="col-xl-2 mt-1 col-lg-4 col-md-4 col-sm-6 col-12 ">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <label class="form-label mt-1"> {{ __('header.Customers') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="CustomerSearch">
                    </div>
                    <div class="input-group input-group-flat not-reverse ">
                        <select wire:model="customer" class="form-select ">
                            <option value="">{{ __('header.Customer') }}</option>
                            @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text p-1">
                            <a href="#" class="link-secondary mt-1 px-2" data-bs-toggle="modal" data-bs-target="#add-update-customer" wire:clicl.prevent="$emit('addustomer')">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                        </span>
                    </div>
                    @error('customer') <span class="text-danger"> {{ $message }}</span> @enderror
                </div>
                <div class="col-xl-2 mt-1 col-lg-4 col-md-4 col-sm-6 col-12 ">
                    <a href="{{ route('returnproduct',app()->getLocale()) }}" class="btn btn-dark shadow-sm"> {{ __('header.Return Product') }}</a>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row mt-3 ">
                @if ($SaleTypeView == 'ImageView')
                <div class="col-md-6 col-12 ">
                    <div class="image-grid">
                        @forelse ($products as $product )
                        <div class="image-card text-center" wire:click.prvent="$set('data','{{ $product->barcode }}')">
                            <div>
                                <img src="{{  $product->image != '' ? asset('storage/product/' . $product->image) : asset('assets/images/image_not_available.png') }}" width="100" alt="">
                            </div>
                            <span class="text-center text-wrap fw-bolder">
                                {{ $product->name }}
                            </span>
                        </div>
                        @empty
                        <p>
                            {{ __('header.NoData') }}
                        </p>
                        @endforelse
                    </div>
                </div>
                @endif
                <div class="{{ $SaleTypeView == 'ImageView' ? 'col-md-6 col-12' : 'col-12' }} ">
                    <div class="table-responsive mt-1 overflow-y-hidden product-sale-table">
                        <table class="table table-vcenter table-nowrap print">
                            <thead>
                                <tr>
                                    @if ( $sales && $sales->sale_details->count() > 0)
                                    <th class=" d-print-none"></th>
                                    @endif
                                    <th class="fs-4 print ">{{ __('header.name') }}</th>
                                    <th class="fs-4  d-print-none text-center">{{ __('header.barcode') }}</th>
                                    <th class="fs-4 print text-center ">{{ __('header.price') }}</th>
                                    <th class="fs-4 print text-center">{{ __('header.TotalQuantity') }}</th>
                                    <th class="fs-4  d-print-none text-center">{{ __('header.TotalPrice') }}</th>
                                    <th class="fs-4  d-print-none text-center">{{ __('header.expire_date') }}</th>
                                    <th class="fs-4 text-center  d-print-none">{{ __('header.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($sales)
                                @forelse ($sales->sale_details as $sale_detail)
                                <tr>
                                    <td class=" d-print-none">
                                        <button class="btn" wire:click="destroy('{{ $sale_detail->id }}','{{ $sale_detail->ProductQuantity?->id }}' ,'{{ $sales->id }}','{{ number_format($sale_detail->quantity *  $sale_detail->product_price,2,',',',') }}')">
                                            <i class="fa fa-trash text-danger"></i>
                                        </button>
                                    </td>
                                    <td class="print">
                                        {{ $sale_detail->product_name }}
                                    </td>
                                    <td class=" d-print-none text-center">
                                        {{ $sale_detail->product_barcode }}
                                    </td>
                                    <td class="print text-center">
                                        {{$sale_detail->product_price }} {{ __('header.dolar') }}
                                    </td>
                                    <td class="print text-center col-1 ">
                                        {{ $sale_detail->quantity }}
                                    </td>
                                    <td class=" d-print-none text-center">
                                        {{ $sale_detail->quantity *  $sale_detail->product_price }} {{ __('header.dolar') }}

                                    </td>
                                    <td class=" d-print-none text-center">
                                        {{ $sale_detail->ProductQuantity?->expiry_date }}
                                    </td>
                                    <td class="col-1 text-center  d-print-none">
                                        @if ($sale_detail->ProductQuantity?->id)
                                        <button class="btn " wire:click.prevent="plus({{ $sale_detail->id }},{{ $sale_detail->ProductQuantity?->id }} ,{{ $sales->id }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn " wire:click.prevent="minus({{ $sale_detail->id }},{{ $sale_detail->ProductQuantity?->id }} ,{{ $sales->id }})">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr class=" d-print-none">
                                    <td colspan="7" class="text-center">
                                        <h4>
                                            {{ __('header.NoData') }}
                                        </h4>
                                    </td>
                                </tr>
                                @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-md-1">
                <div class="col-md-6 col-12 ">
                    <div class="">
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
                                    <span class="mx-2">
                                        {{ $sales->total }} {{ __('header.dolar') }}
                                    </span>
                                    {{ $ConvertDollarToDinar($sales->total) }} {{ __('header.dinar') }}
                                    @else
                                    0 {{ __('header.dolar') }}
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 ">
                    <div class="col-md-6 col-12 mt-4  d-print-none ">
                        <div class="d-flex justify-content-between aligin-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="debt">
                                <label class="form-check-label pt-1">
                                    {{ __('header.debt') }}
                                </label>
                            </div>
                        </div>
                        @if ($debt)
                        <div class="col-12">
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
                    @if ($sales)
                    <div class="mt-1 d-print-none d-flex align-items-center gap-3 flex-wrap">
                        <button class="btn btn-cyan px-4 " wire:click="submit(true)">
                            {{ __('header.sale') }}
                        </button>
                        <button class="btn btn-blue px-4 " wire:click=" salePrint">
                            {{ __('header.saleAndPrint') }}
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    $(document).ready(function() {
        window.addEventListener('print', function(event) {
            window.open(event.detail.route, '_blank');
            window.location.reload();
        });

        $('#barcode').click(function(e) {
            e.preventDefault();
            // check if the input isn't empty
            if (this.value != '')
                Livewire.emit('dataCheck');
        });
    });
</script>
@endpush