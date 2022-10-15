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
                        {{ number_format($TotalSaleProduct , 0) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>