<div class="col-12 col-lg-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fw-bolder fs-3">
                    {{ __('header.total_sales') }}
                </span>
                <div class="lh-1 col-lg-3 col-6">
                    <select class="form-select  rounded w-100 " wire:model="sales">
                        <option value="today" selected>
                            {{ __('header.today') }}
                        </option>

                        <option value="Yesterday">
                            {{ __('header.yesterday') }}
                        </option>
                        <option value="ThisWeek">
                            {{ __('header.this_week') }}
                        </option>
                        <option value="ThisMonth">
                            {{ __('header.this_month') }}
                        </option>
                        <option value="3-month-ago">
                            {{ __('header.3 month ago') }}
                        </option>
                        <option value="6-month-ago">
                            {{ __('header.6 month ago') }}
                        </option>
                        <option value="ThisYear">
                            {{ __('header.this_year') }}
                        </option>
                        <option value="one-year-ago">
                            {{ __('header.one year ago') }}
                        </option>
                        <option value="3-year-ago">
                            {{ __('header.3 year ago') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="d-flex align-items-center mt-3 flex-wrap ">
                    <div class="col-lg-4 col-md-6">
                        <span class="fs-3 fw-bolder">
                            {{ __('header.Sales') }} :
                            <span class="text-muted fs-2 text-break">
                                {{ number_format($TotalSale , 0) }}
                            </span>
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <span class="fs-3 fw-bolder">
                            {{ __('header.Total Products') }} :
                            <span class="text-muted fs-2 text-break">
                                {{ number_format($TotalSaleProduct , 0) }}
                            </span>
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <span class="fs-3 fw-bolder">
                            {{ __('header.TotalPrice') }} :
                            <span class="text-muted fs-2 text-break">
                                {{ number_format($TotalSalesPrice , 0) }}
                            </span>
                            {{ __('header.currency') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>