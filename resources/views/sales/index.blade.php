@push('title') Products @endpush
<div>
    <div wire:loading wire:target="destroy">
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
    <div class="{{ app()->getLocale() == 'ckb' ? 'reverse' : '' }} px-lg-5 px-3">
        <div class="d-flex align-items-center justify-content-between mt-3">
            <p class="fw-bolder fs-1">
                {{ __('header.Sales') }}
            </p>
            <div class="">
                <a href="{{ route('sales' , app()->getLocale()) }}" class="btn text-success">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        <div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart" aria-labelledby="offcanvasStartLabel" style="visibility: visible;" aria-modal="true" role="dialog">
            <div class="offcanvas-header p-1">
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row gy-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('header.StartDaye') }}</label>
                        <input type="date" wire:model="start" class="form-control not-reverse">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('header.EndDaye') }}</label>
                        <input type="date" wire:model="end" class="form-control  not-reverse">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3 gy-3 align-items-center">
            <div class="col-lg-2 col-8">
                <select wire:model="UserID" class="form-select">
                    <option value="">{{ __('header.Users') }}</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-6">
                <select class="form-select" wire:model="date">
                    <option value="">
                        {{ __('header.date') }}
                    </option>
                    <option value="today">
                        {{ __('header.today') }}
                    </option>
                    <option value="yesterday">
                        {{ __('header.yesterday') }}
                    </option>
                    <option value="this_week">
                        {{ __('header.this_week') }}
                    </option>
                    <option value="this_month">
                        {{ __('header.this_month') }}
                    </option>
                </select>
            </div>
        </div>
        @include('sales.pages.delete')
        @include('sales.pages.view')
        <div class="row mt-3" wire:loading wire:target="previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3" wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
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
                            <a href="{{ $sale->paid ?'' : route('sales.debt' ,['lang'=>app()->getLocale(),'s'=>$sale->name]) }}" class="{{ $sale->paid ? 'text-dark' : 'text-blue' }}">
                                {{ $sale->invoice }}
                            </a>
                        </td>
                        <td class="fw-bolder">
                            {{ $sale->user_name }}
                        </td>
                        <td>
                            {{ number_format($sale->total,0)  }} {{ __('header.currency') }}
                        </td>
                        <td>
                            {{ $sale->discount != null ?  number_format($sale->discount,0) . __('header.currency') : __('header.Not-discount') }}
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
                            <a href="{{ route('sales.view' ,['lang'=>app()->getLocale(),'id'=>$sale->id , 'invoice'=> $sale->invoice]) }}" class="btn text-success">
                                <i class="fa-solid fa-file-invoice"></i>
                            </a>
                            <a href="" class="btn text-danger " data-bs-toggle="modal" data-bs-target="#delete" wire:click.prevent="$set('SaleID',{{ $sale->id }})">
                                <i class="fas fa-trash"></i>
                            </a>
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

            <div class="mt-4" wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
                {{ $sales->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>