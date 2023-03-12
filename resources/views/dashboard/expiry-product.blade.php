<div class="col-sm-6 col-lg-3">
    <div class="card">
        <div class="card-body">
            <span class="fw-bolder fs-3">
                {{ __('header.ExpiryProducts') }}
            </span>
            <div class="row align-items-center  mt-4">
                <div class="col-auto">
                    <span class="bg-cyan avatar">
                        <i class="fa-solid fa-box fs-2"></i>
                    </span>
                </div>
                <div class="col">
                    <span class="fs-1 fw-bolder">
                        <a href="{{ route('ExpiryProducts',['lang'=>app()->getLocale()]) }}">
                            {{ number_format($ExpiryCount , 0) }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>