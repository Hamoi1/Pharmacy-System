<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="turbo-cache-control" content="no-cache">
    <title>@stack('title')</title>
    <link rel="shortcut icon" href="{{  $settings->logo != null ? asset('storage/logo/'.$settings->logo) : asset('assets/images/capsules.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/print.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" defer>

    @stack('css')
    @livewireStyles
    @if (app()->getLocale() == 'ckb')
    <style>
        .notyf__message {
            direction: rtl !important;
        }
    </style>
    @endif

    @livewireScripts

    <script type="module">
        import hotwiredTurbo from 'https://cdn.skypack.dev/@hotwired/turbo';
    </script>
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbolinks-eval="false" data-turbo-eval="false"></script>
    <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js" defer></script>
    <script defer>
        $(document).ready(function() {
            window.addEventListener('message', respnose => {
                var notyf = new Notyf({
                    duration: 3000,
                    position: {
                        x: 'center',
                        y: 'top',
                    },
                    types: [{
                            type: 'info',
                            background: '#3f6ad8',
                            icon: {
                                className: 'fas fa-info-circle',
                                tagName: 'span',
                                color: '#fff'
                            },
                        },
                        {
                            type: 'success',
                            background: '#2ecc71',
                            icon: {
                                className: 'fas fa-check-circle',
                                tagName: 'span',
                                color: '#fff'
                            },
                        },
                        {
                            type: 'error',
                            background: '#e74c3c',
                            icon: {
                                className: 'fas fa-times-circle',
                                tagName: 'span',
                                color: '#fff'
                            },
                        },
                        {
                            type: 'warning',
                            background: '#f1c40f',
                            icon: {
                                className: 'fas fa-exclamation-circle',
                                tagName: 'span',
                                color: '#fff'
                            },
                        },
                    ]
                });
                if (respnose.detail.type == 'success') {
                    notyf.success(respnose.detail.message);
                }
                if (respnose.detail.type == 'error') {
                    notyf.error(respnose.detail.message);
                }
                if (respnose.detail.type == 'info') {
                    notyf.info(respnose.detail.message);
                }
                if (respnose.detail.type == 'warning') {
                    notyf.warning(respnose.detail.message);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // console.clear();
            window.addEventListener('closeModal', event => {
                event.preventDefault();
                $('.modal').modal('hide');
                $('.modal-backdrop ').remove();
                $('.input-export').prop('checked', false);
                $('.modal form').trigger('reset');
                $('.modal form error-*').html('');
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
            Livewire.on('ChangeTheme', theme => {
                window.location.reload();
            });
            Livewire.on('UpdateProfile', data => {
                window.location.reload();
            });

        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.0.2/pusher.min.js" integrity="sha512-FFchpqjQzRMR75a1q5Se4RZyBsc7UZhHE8faOLv197JcxmPJT0/Z4tGiB1mwKn+OZMEocLT+MmGl/bHa/kPKuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;
        var pusher = new Pusher('f4447b80e7791998f4fe', {
            encrypted: true,
            cluster: 'ap2',
        });
        var ChannelUserStatus = pusher.subscribe('user-status-' + `{{ auth()->id() }}`);
        ChannelUserStatus.bind('user-status', function(data) {
            if (data.user.id == `{{ auth()->id() }}`) {
                window.location.reload();
            }
        });
        var ArrayActions = ['user-actions', 'user-page', 'category-page', 'supplier-page', 'customer-page'];
        PusherActions = () => {
            ArrayActions.forEach(index => {
                var ChannelUserActions = pusher.subscribe(index);
                ChannelUserActions.bind(index, function(data) {
                    Livewire.emit(index);
                });
            });
        }
        //    check if browser support navigator.connection
        if (navigator.connection) {
            // get effective type of connection
            var effectiveType = navigator.connection.effectiveType;
            // check
            // if effective type is 4 g or 3 g or solwer or not connected
            var array = ['2g', 'slow-2g', 'medium-2g', 'fast-2g', ];
            if (!array.includes(effectiveType)) {
                PusherActions();
            }
        } else {
            PusherActions();
        }
    </script>

    @stack('js')
</head>

<body class=" @if (auth()->check()) {{ auth()->user()->theme == 0 ? 'theme-light' : 'theme-dark'  }} @endif " wire:ignore.self>
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
    @if(Route::currentRouteName() != 'sales')
    <div class="lodaing-seaction d-print-none">
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
    <aside class="navbar navbar-vertical  d-print-none  {{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'navbar-right' : '' }} navbar-expand-lg navbar-light  d-print-none">
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
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/point-of-sale')   ? 'active-page' : '' }}  mx-md-2 mx-1  " href="{{ route('sales',app()->getLocale()) }}">
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
                            <a class="nav-link {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/sales/debt') ? 'active-page ' : '' }}  mx-md-2 mx-1   " href="{{ route('sales.debt',app()->getLocale()) }}">
                                <i class="fa-solid fa-money-bill-wave mb-2"></i>
                                <span class="nav-link-title  mx-md-2 mx-1 ">
                                    {{ __('header.Debts') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                        @can('View Product')
                        <li class="nav-item dropdown my-1 mx-lg-1 mx-md-4 mx-3 ">
                            <a class="nav-link   {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}  active {{ Request()->is(app()->getLocale().'/products') ? 'active-page' : '' }}  mx-md-2 mx-1  " href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <i class="fa fa-box mb-2"></i>
                                <span class="nav-link-title mx-2">
                                    {{ __('header.Products') }}
                                </span>
                            </a>
                            <div class="dropdown-menu ">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'me-4' : 'ms-1' }} text-dark" href="{{ route('products',app()->getLocale()) }} ">
                                        {{ __('header.Products') }}
                                    </a>
                                    <a class="dropdown-item {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'me-4' : 'ms-1' }} text-dark" href="{{ route('returnproduct',app()->getLocale()) }} ">
                                        {{ __('header.Return Product')}}
                                    </a>
                                </div>
                            </div>
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
                        @can('View Customer')
                        <li class="nav-item my-1 mx-lg-1 mx-md-4 mx-3">
                            <a class="nav-link  {{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} active {{ Request()->is(app()->getLocale().'/customers') ? 'active-page' : '' }}  mx-2" href="{{ route('customers',app()->getLocale()) }}">
                                <i class="fa fa-users mb-2"></i>
                                <span class="nav-link-title  mx-2">
                                    {{ __('header.Customers') }}
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
        <div id="offcanvas" class="{{ app()->getLocale() == 'ckb' || app()->getLocale() == 'ar' ? 'left' : 'right' }}">
            <a class="btn" data-bs-toggle="offcanvas" href="#Products" role="button" aria-controls="Products">
                <span class="badge bg-orange badge-notification badge-blink"></span>
                <i class="fa fa-box"></i>
            </a>
        </div>
        {{ $slot }}
    </div>
    </div>
    @else
    <div class="page-wrapper ">
        {{ $slot }}
    </div>
    @endif
    @endauth
    @guest
    {{ $slot }}
    @endguest


</body>

</html>