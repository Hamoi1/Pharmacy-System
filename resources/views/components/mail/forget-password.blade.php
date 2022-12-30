<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');

        @font-face {
            font-family: 'kurdish';
            src: url("{{ asset('assets/rudawbold.ttf') }}");
        }

        html {
            font-family: 'Roboto', sans-serif;
        }

        p {
            font-size: 17px !important;
            font-weight: 400 !important;
            color: #000 !important;
        }

        .code {
            text-align: center;
            width: auto !important;
            display: block !important;
            background: #2C3E50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            margin: 10px auto;
        }

        .reverse {
            font-family: 'kurdish' !important;
            direction: rtl;
        }
    </style>
</head>

<body>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}">

        <h3> {{ __('header.hi') }}, {{ $user->name }}</h3>
        <br>
        <p>
            {{ __('header.EmailMessage') }}
        </p>
        <center>
            <span class="code" dir="ltr">
                {{ $code }}
            </span>
        </center>
        <p>
            {{ __('header.EmailWarning') }}
        </p>
    </div>

</body>

</html>