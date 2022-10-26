<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .container {
            width: 100%;
            height: 100%;
        }

        .barcode {
            display: inline-block;
            width: 200px !important;
            margin: 25px 10px;
        }

        span {
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>
        Barcode : {{ $barcode->barcode  }}
    </h2>
    <div class="container">
        @for ($i = 0; $i < $barcode->quantity; $i++)
            <div class="barcode ">
                {!! DNS1D::getBarcodeHTML($barcode->barcode, 'I25') !!}
                <center>
                    <span>
                        {{ $barcode->barcode }}
                    </span>
                </center>
            </div>
            @endfor
    </div>

</body>

</html>