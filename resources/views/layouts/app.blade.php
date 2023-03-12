<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@stack('title')</title>
    <link rel="shortcut icon" href="{{  $settings->logo != null ? asset('storage/logo/'.$settings->logo) : asset('assets/images/capsules.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/print.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('css')
    @livewireStyles
    @if (app()->getLocale() == 'ckb')
    <style>
        .notyf__message {
            direction: rtl !important;
        }
    </style>
    @endif

</head>

<body class=" @if (auth()->check()) {{ auth()->user()->theme == 0 ? 'theme-light' : 'theme-dark'  }} @endif ">
    <div wire:offline>
        <div class="offline">
            <div class="offline-content">
                <div class="offline-icon">
                    <img src="{{ asset('assets/images/offline.gif') }}" width="100px" alt="">
                </div>
                <h1 class="offline-title ">{{ __('header.offline') }}</h1>
            </div>
        </div>
    </div>
    @auth
    <div class="lodaing-seaction">
        <div class="building-blocks">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <aside class="navbar navbar-vertical  {{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'navbar-right' : '' }} navbar-expand-lg navbar-light no-print">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="mt-lg-3 {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}">
                <livewire:profile.index />
                <hr class="m-0 mt-1 d-none d-lg-block w-100 ">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="navbar-nav pt-lg-3 py-md-4 py-2">
                        @can('View Dashboard')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/dashboard') ? 'active-page' : '' }}  mx-md-2 mx-1  " href="{{ route('dashboard',app()->getLocale()) }}">
                                <i class="fa fa-home mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Dashboard') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3 ">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Route::currentRouteName() == 'sales'  ? 'active-page' : '' }}  mx-md-2 mx-1  " href="{{ route('sales',app()->getLocale()) }}">
                                <i class="fa fa-shopping-cart mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.PointOfSale') }}
                                </span>
                            </a>
                        </li>
                        @can('View Sales')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3 ">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/sales') ? 'active-page ' : '' }}  mx-md-2 mx-1   " href="{{ route('sales.index',app()->getLocale()) }}">
                                <i class="fa fa-shopping-cart mb-2"></i>
                                <span class="nav-link-title  mx-md-2 mx-1 ">
                                    {{ __('header.Sales') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View DebtSale')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3 ">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/sales/debt') ? 'active-page ' : '' }}  mx-md-2 mx-1   " href="{{ route('sales.debt',app()->getLocale()) }}">
                                <i class="fa-solid fa-money-bill-wave mb-2"></i>
                                <span class="nav-link-title  mx-md-2 mx-1 ">
                                    {{ __('header.Debts') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Product')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3 ">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/products') ? 'active-page' : '' }}  mx-md-2 mx-1  " href="{{ route('products',app()->getLocale()) }}">
                                <i class="fa fa-box mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Products') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View User')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/users') ? 'active-page' : '' }}  mx-md-2 mx-1  " href="{{ route('users',app()->getLocale()) }}">
                                <i class="fa fa-user mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Users') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View ExpiryProduct')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/ExpiryProducts') ? 'active-page' : '' }}  mx-2" href="{{ route('ExpiryProducts',app()->getLocale()) }}">
                                <i class="fa-solid fa-triangle-exclamation mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.ExpiryProducts') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View StockOutProduct')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/stock-out-products') ? 'active-page' : '' }}  mx-md-2 mx-1 " href="{{ route('StockOutProcuts',app()->getLocale()) }}">
                                <i class="fa-solid fa-exclamation mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.StockedOutProducts') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Category')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/categorys') ? 'active-page' : '' }}  mx-2" href="{{ route('categorys',app()->getLocale()) }}">
                                <i class="fa fa-list mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Categorys') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Supplier')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/suppliers') ? 'active-page' : '' }}  mx-2" href="{{ route('suppliers',app()->getLocale()) }}">
                                <i class="fa fa-truck mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Suppliers') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Barcode')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/barcode') ? 'active-page' : '' }}  mx-2" href="{{ route('barcode',app()->getLocale()) }}">
                                <i class="fa fa-barcode mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.barcode') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Log')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  active {{ Request()->is(app()->getLocale().'/logs') ? 'active-page' : '' }}  mx-2" href="{{ route('logs',app()->getLocale()) }}">
                                <i class="fa-solid fa-file mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Logs') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Setting')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/settings') ? 'active-page' : '' }}  mx-2" href="{{ route('settings',app()->getLocale()) }}">
                                <i class="fa fa-cog mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.setting') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/profile') ? 'active-page' : '' }}  mx-2" href="{{ route('profile.update',app()->getLocale()) }}">
                                <i class="fa fa-user-edit mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.profile') }}
                                </span>
                            </a>
                        </li>
                        <div class="w-100  text-center   mt-lg-4  mt-2  ">
                            <span class="  rounded p-2 m-auto">
                                Developed By <a href="https://github.com/Hamoi1" class="text-primary ">
                                    Muhammad
                                </a>
                            </span>
                        </div>
                    </ul>
                </div>
            </div>

        </div>
    </aside>
    <div class="page-wrapper ">
        {{ $slot }}
    </div>
    </div>
    @endauth
    @guest
    {{ $slot }}
    @endguest

    @livewireScripts
    <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.addEventListener('closeModal', event => {
                $('.modal').modal('hide');
                $('.modal-backdrop ').remove();
                $('.input-export').prop('checked', false);
            });
            window.addEventListener('play', event => {
                if (event.detail.sound == 'beep') {
                    PlayAudio("/assets/audio/beep.mp3");
                } else if (event.detail.sound == 'fail') {
                    PlayAudio("/assets/audio/fail.mp3");
                } else if (event.detail.sound == 'undo') {
                    PlayAudio("/assets/audio/undo.mp3");
                }
            });
        });
        window.addEventListener('load', () => {
            // if user offiline 
            $('.lodaing-seaction').fadeToggle(1000);
        });
        Livewire.on('ChangeTheme', theme => {
            window.location.reload();
        });
        Livewire.on('UpdateProfile', data => {
            window.location.reload();
        });
    </script>
    @stack('js')
</body>

</html>