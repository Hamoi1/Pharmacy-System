<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .flex {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .barcode {
            width: 200px !important;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h2>
        Barcode : {{ $barcode->barcode  }}
    </h2>
    <div class="flex">
        @for ($i = 0; $i < $barcode->quantity; $i++)
            <div class="barcode ">
                {!! DNS1D::getBarcodeHTML($barcode->barcode, 'I25') !!}
            </div>
            @endfor
    </div>

</body>

</html>