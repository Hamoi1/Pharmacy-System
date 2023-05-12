@push('title') Sales @endpush
<div class="saless">
    <div wire:loading wire:target="destroy" class="d-print-none">
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
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3 ">
        <div class="mt-4 d-print-none">
            <div class="page-header">
                <div class="row align">
                    <div class="col">
                        <p class="fw-bolder fs-1">
                            {{ __('header.Sales') }}
                        </p>
                    </div>
                    <div class="col-auto ms-auto">
                        <a href="{{ route('sales' , app()->getLocale()) }}" class="btn bg-primary">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3 gy-3 align-items-center d-print-none">
            <div class="col-lg-2 col-md-4 col-12 not-reverse">
                <div class="input-icon ">
                    <span class="input-icon-addon  ps-2">
                        Inv-
                    </span>
                    <input type="text" wire:model="invoice" class="form-control" placeholder="">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <select wire:model="UserID" class="form-select">
                    <option value="">{{ __('header.Users') }}</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <input type="date" class="form-control" wire:model="date">
            </div>
        </div>
        @can('Delete Sales')
        @include('sales.pages.delete')
        @endcan
        @include('sales.pages.view')
        <div class="row mt-3 d-print-none" wire:loading wire:target="previousPage,nextPage,gotoPage,UserID,date">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3 d-print-none" wire:loading.remove wire:target="previousPage,nextPage,gotoPage,UserID,date">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="col-1 fs-4">
                            Invoice-ID
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.User') }}
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
                            {{ __('header.date') }}
                        </th>
                        <th class="col-1 fs-4 text-center">{{ __('header.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale )
                    <tr>
                        <td>
                            <a href="{{ $sale->paid ?'#' : route('sales.debt' ,['lang'=>app()->getLocale(),'s'=>$sale->name]) }}" class="{{ $sale->paid ? '' : 'text-blue' }}">
                                {{ $sale->invoice }}
                            </a>
                        </td>
                        <td class="fw-bolder">
                            {{ $sale->user_name }}
                        </td>
                        <td>
                            {{ number_format($sale->total,2,',',',')  }} {{ __('header.currency') }}
                        </td>
                        <td>
                            {{ $sale->discount != null ?  number_format($sale->discount,2,',',',') . __('header.currency') : __('header.Not-discount') }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $sale->paid ? 'success' : 'info' }}">
                                {{ $sale->paid ? __('header.Cash') : __('header.debt') }}
                            </span>
                        </td>
                        <td>
                            {{ $sale->created_at->format('Y-m-d') }}
                        </td>
                        <td class="text-center">
                            <a href="" class="btn text-info" data-bs-toggle="modal" data-bs-target="#view" wire:click.prevent="View({{ $sale->id }})">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('sales.print' ,['lang'=>app()->getLocale(),'id'=>$sale->id]) }}" target="_blank" class="btn text-success">
                                <i class="fa-solid fa-file-invoice"></i>
                            </a>
                            <!-- <a href="#" class="btn text-success" wire:click.prevent="convertToPdf({{ $sale->id }})">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a> -->
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

            <div class="mt-4" wire:loading.remove wire:target="previousPage,nextPage,gotoPage,UserID,date">
                {{ $sales->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>