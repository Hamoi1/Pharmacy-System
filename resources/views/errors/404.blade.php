<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page 404 - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/print.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>

<body class="border-top-wide border-primary d-flex flex-column theme-light">
    <script src="./dist/js/demo-theme.min.js"></script>
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">404</div>
                <p class="empty-title">Oops… You just found an error page</p>
                <p class="empty-subtitle text-muted">
                    We are sorry but the page you are looking for was not found
                </p>
                <div class="empty-action">
                    <a href="{{ route('dashboard',app()->getLocale()) }}" class="btn shadow-sm">
                        <span class="mx-2 fs-3 mt-2">⬅️</span>
                        <span class="mt-2">
                            Go Home
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>