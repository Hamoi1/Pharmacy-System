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