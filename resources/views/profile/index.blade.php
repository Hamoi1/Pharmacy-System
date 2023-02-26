<div class="nav-item dropdown py-1 " id="profile-section">
    <div class="d-flex align-items-center justify-content-between {{ app()->getLocale() =='ckb' ? 'flex-reverse ' : '' }}">
        <div class="col-9">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <img src="{{ auth()->user()->image(auth()->user()->user_details->image) }}" class="avatar  rounded-circle mx-2 object-cover img-fulid" alt="">
                <div class="">
                    <span class="fw-bolder  d-none d-lg-inline ">{{ Str::limit(auth()->user()->name,10) }}</span>
                    <span class="d-none d-lg-block mt-1 small text-muted user-subttile">{{ auth()->user()->role() }}</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end ">
                <a href="{{ route('profile.update' ,app()->getLocale()) }}" class="dropdown-item">{{ __('header.profile')  }}</a>
                <a href="{{ route('settings' ,app()->getLocale()) }}" class="dropdown-item">{{ __('header.setting') }}</a>
                <a href="{{ route('logout' ,app()->getLocale()) }}" class="dropdown-item bg-danger ">{{ __('header.loguot')  }}</a>
            </div>
        </div>
        @if (!request()->routeIs('products.image.update') && !request()->routeIs('sales.view') && !request()->routeIs('print'))
        <div class="col-3 ">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <i class="fa fa-language fs-2" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end ">
                <a href="{{ route(Route::currentRouteName(),'ckb')}}" class="dropdown-item">
                    Kurdish
                </a>
                <a href="{{ route(Route::currentRouteName(),'en') }}" class="dropdown-item">
                    English
                </a>
                <a href="{{ route(Route::currentRouteName(),'ar')}}" class="dropdown-item">
                    Arabic
                </a>
            </div>
        </div>
        @endif

    </div>
</div>