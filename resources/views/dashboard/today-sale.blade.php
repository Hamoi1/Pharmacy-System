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