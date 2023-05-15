<x-modal.view target="view" title="{{ __('header.title_view' , ['name'=>__('header.Customer')]) }}" modalWidth="modal-fullscreen" wire="wire:click=done">
    <div wire:loading wire:target="show">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    @if($customer)
    <div wire:loading.remove wire:target="show" class="viewUser-Grid px-md-4 py-md-2  {{ app()->getLocale() == 'ckb'   || app()->getLocale() == 'ar' ? 'reverse' : '' }}">
        <div class="">
            <p class=" fw-bolder fs-3">
                {{ __('header.customerDetails') }}
            </p>
            <div class="align-center">
                <p>
                    <span class="fw-bold">{{ __('header.name') }} :</span>
                    {{ $customer->name }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.email') }} :</span>
                    {{ $customer->email }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.phone') }} :</span>
                    {{ $customer->phone }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.address') }} :</span>
                    {{ $customer->address }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.guarantoraddress') }} :</span>
                    {{ $customer->guarantoraddress  ?? __('header.not have' ,['name'=> __('header.guarantoraddress')]) }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.guarantorphone') }} :</span>
                    {{ $customer->guarantorphone  ?? __('header.not have' ,['name'=> __('header.guarantorphone')]) }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.created_at') }} :</span>
                    {{ $customer->created_at->diffForHumans() }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.barwar') }} :</span>
                    {{ $customer->created_at->format('Y-m-d') }}
                </p>
                <p>
                    <span class="fw-bold">{{ __('header.TotalDebtPrice') }} :</span>
                    {{ $totalDebt ? number_format($totalDebt,2,',',',').' '. __('header.dolar')  : 0 }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-12">
        <ul class="nav nav-pills mb-3 border-bottom" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $debt ? '' : 'active' }}" id="pills-sales-tab" data-bs-toggle="pill" data-bs-target="#pills-sales" type="button" role="tab" aria-controls="pills-sales" aria-selected="true" wire:click="debt_sale()">
                    {{ __('header.Purchases') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $debt ? 'active' : '' }}" id="pills-debt-tab" data-bs-toggle="pill" data-bs-target="#pills-debt" type="button" role="tab" aria-controls="pills-debt" @if($debt) aria-selected="true" @else aria-selected="false" @endif wire:click="debt_sale(true)">
                    {{ __('header.Debts') }}
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            @if($customer->sales != null)
            <div class="tab-pane fade show active" id="pills-sales" role="tabpanel" aria-labelledby="pills-sales-tab" tabindex="0">
                <div class="row mt-3" wire:loading wire:target="prevPageSales,nextPageSales,debt_sale">
                    <div class="d-flex  gap-2">
                        <h3>
                            {{ __('header.waiting') }}
                        </h3>
                        <div class="spinner-border" role="status"></div>
                    </div>
                </div>
                <div class="table-responsive mt-3" wire:loading.remove wire:target="prevPageSales,nextPageSales,debt_sale">
                    <table class="table table-vcenter table-nowrap">
                        <thead>
                            <tr>
                                <th class="col-1 fs-4">
                                    Invoice-ID
                                </th>
                                <th class="col-1 fs-4">
                                    {{ __('header.salesBy') }}
                                </th>
                                <th class="col-1 fs-4">
                                    {{ __('header.price') }}
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
                            @forelse ($customer->sales as $sale )
                            @php
                            $invoice = Str::remove('inv-',$sale->invoice);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('sales.index',['lang'=>app()->getLocale(),'invoice'=>$invoice]) }}" class="text-blue">
                                        {{ $sale->invoice }}
                                    </a>
                                </td>
                                <td>
                                    {{ $sale->user_name }}
                                </td>
                                <td>
                                    {{ number_format($sale->total,2,',',',')  }} {{ __('header.dolar') }}
                                    {{ $ConvertDolarToDinar($sale->total) }} {{ __('header.dinar') }}
                                </td>
                                <td>
                                    {{ $sale->discount != null ?  number_format($sale->discount,2,',',',') .' '. __('header.dolar') : __('header.Not-discount') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sale->paid ? 'success' : 'info' }}">
                                        {{ $sale->paid ? __('header.Cash') : __('header.debt') }}
                                    </span>
                                </td>
                                <td>
                                    {{ $sale->debt_sale != null ?  number_format($sale->debt_sale->amount,2,',',',') .' '. __('header.dolar') : __('header.nothing') }}
                                    @if ($sale->debt_sale != null)
                                    {{ $ConvertDolarToDinar($sale->debt_sale->amount) }} {{ __('header.dinar') }}
                                    @endif
                                </td>
                                <td>
                                    {{ $sale->debt_sale != null ?  number_format($sale->debt_sale->paid,2,',',',') .' '. __('header.dolar') : __('header.nothing') }}
                                    @if ($sale->debt_sale != null)
                                    {{ $ConvertDolarToDinar($sale->debt_sale->paid) }} {{ __('header.dinar') }}
                                    @endif
                                </td>
                                <td>
                                    {{ $sale->debt_sale != null ?  number_format($sale->debt_sale->remain,2,',',',') .' '. __('header.dolar') : __('header.nothing') }}
                                    @if ($sale->debt_sale != null)
                                    {{ $ConvertDolarToDinar($sale->debt_sale->remain) }} {{ __('header.dinar') }}
                                    @endif
                                </td>
                                <td>
                                    {{ $sale->created_at->format('Y-m-d') }}
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
                    @if ($customer->sales != null)
                    <div class="my-2 mt-4 px-4 not-reverse " wire:loading.remove wire:target="prevPageSales,nextPageSales">
                        <button class="btn p-2 cursor-pointer" wire:click="prevPageSales('{{ $debt }}')" @if ($salesPage==1) disabled @endif>
                            <i class="fa fa-angle-left"></i>
                        </button>
                        <button class="btn p-2 cursor-pointer" wire:click="nextPageSales('{{ $debt }}')" @if ($salesPage==$lastPageSale) disabled @endif>
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </div>
                    @endif
                    @else
                    <div class="text-center">
                        <h4>
                            {{ __('header.NoData') }}
                        </h4>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</x-modal.view>