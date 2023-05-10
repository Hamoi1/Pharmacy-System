@push('title') Dashboard @endpush
<div class="dashboard">
    <div class="px-2 px-lg-5 py-2 {{ app()->getLocale() == 'ckb' || app()->getLocale() == 'ar'   ? 'reverse' : '' }} ">
        <div class="mt-3">
            <div class="col-sm-12 my-3">
                <livewire:system.clean-up />
            </div>
            <div class="row row-deck row-cards">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.New Products') }}
                                </span>
                                <span class="text-muted">
                                    {{ __('header.today') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-3">
                                <div class="col-auto">
                                    <span class="bg-info avatar">
                                        <i class="fa-solid fa-box fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($TotalProducts , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.New Users') }}
                                </span>
                                <span class="text-muted">
                                    {{ __('header.today') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-3">
                                <div class="col-auto">
                                    <span class="bg-dark avatar">
                                        <i class="fa-solid fa-user fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($TotalUsers , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.New Suppliers') }}
                                </span>
                                <span class="text-muted">
                                    {{ __('header.today') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-3">
                                <div class="col-auto">
                                    <span class="bg-dark avatar">
                                        <i class="fa-solid fa-user fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($TotalSuppliers , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.New Categorys') }}
                                </span>
                                <span class="text-muted">
                                    {{ __('header.today') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-3">
                                <div class="col-auto">
                                    <span class="bg-cyan avatar">
                                        <i class="fa-solid fa-list fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($TotalCategorys , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.Today Sales') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-4">
                                <div class="col-auto">
                                    <span class="bg-blue avatar">
                                        <i class="fa-solid fa-money-bill-1-wave fs-2"></i>
                                    </span>
                                </div>
                                <div class="col ">
                                    <span class="fs-2 fw-bolder ">
                                        {{ number_format($TotalSalePrice , 2,',',',') }} {{ __('header.currency') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.Sales') }}
                                </span>
                                <span class="text-muted">
                                    {{ __('header.today') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-3">
                                <div class="col-auto">
                                    <span class="bg-blue avatar">
                                        <i class="fa-solid fa-box fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($TotalSoldProduct , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.total_sales') }}
                                </span>
                                <div class="lh-1 col-lg-3 col-6">
                                    <input type="date" class="form-control" wire:model="date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex align-items-center mt-3 flex-wrap ">
                                    <div class="col-lg-4 col-md-6">
                                        <span class="fs-3 fw-bolder">
                                            {{ __('header.Sales') }} :
                                            <span class="text-muted fs-2 text-break">
                                                {{ number_format($TotalSale , 0,',',',') }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <span class="fs-3 fw-bolder">
                                            {{ __('header.Total Products') }} :
                                            <span class="text-muted fs-2 text-break">
                                                {{ number_format($TotalSaleProduct , 0,',',',') }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <span class="fs-3 fw-bolder">
                                            {{ __('header.TotalPrice') }} :
                                            <span class="text-muted fs-2 text-break">
                                                {{ number_format($TotalSalesPrice , 2,',',',') }}
                                            </span>
                                            {{ __('header.currency') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.Total Products') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-4">
                                <div class="col-auto">
                                    <span class="bg-blue avatar">
                                        <i class="fa-solid fa-box fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($ProductCount , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="fw-bolder fs-3">
                                    {{ __('header.Total Users') }}
                                </span>
                            </div>
                            <div class="row align-items-center  mt-4">
                                <div class="col-auto">
                                    <span class="bg-blue avatar">
                                        <i class="fa-solid fa-users fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <span class="fs-1 fw-bolder">
                                        {{ number_format($UsersCount  , 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 gy-3 not-reverse">
                    <div class="col-12">
                        <h3 class="">{{ __('Top 10 User Sales') }}</h3>
                        <div class="card">
                            <div class="card-body">
                                <div id="chart" class="chart-lg chart"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <h3 class="">{{ __('Top 10 Product Sales') }}</h3>
                        <div class="card">
                            <div class="card-body">
                                <div id="chart-product" class="chart-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>