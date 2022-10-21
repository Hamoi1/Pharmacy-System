<div class="col-sm-6 col-lg-3">
    <div class="card">
        <div class="card-body">
            <span class="fw-bolder fs-3">
                {{ __('header.ExpiryProducts') }}
            </span>
            <div class="row align-items-center  mt-4">
                <div class="d-flex flex-column">
                    <span class="fw-bolder fs-3">
                        {{ __('header.ExpiryProducts') }}
                    </span>
                </div>
                <div class="row align-items-center  mt-3">
                    <div class="col-auto">
                        <span class="bg-cyan avatar">
                            <i class="fa-solid fa-box fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <span class="fs-1 fw-bolder">
                            {{ number_format($ExpiryCount , 0) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>