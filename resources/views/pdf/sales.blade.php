<!DOCTYPE html>
<html>

<head>
    <style>
        #sales {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #sales td,
        #sales th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #sales tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #sales tr:hover {
            background-color: #ddd;
        }

        #sales th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h3>
        Name : {{ $user->name }}
    </h3>
    <p>
        Email : {{ $user->email }}
    </p>
    <p>
        Role : {{ $user->role() }}
    </p>
    <p>
        Phone : {{ $user->phone }}
    </p>
    <hr>
    <table id="sales">
        <tr>
            <th>
                Invoice-ID
            </th>
            <th>
                Price
            </th>
            <th>
                Dicount
            </th>
            <th>
                Status
            </th>
            <th>
                Date
            </th>
        </tr>
        @foreach($user->sales as $sale)
        <tr>
            <td>
                {{ $sale->invoice }}
            </td>
            <td>
                {{ number_format($sale->total,0)  }} IQD
            </td>
            <td>
                {{ $sale->discount != null ?  number_format($sale->discount,0) . 'IQD' : __('header.Not-discount') }}
            </td>
            <td>
                <span class="badge bg-{{ $sale->paid ? 'success' : 'info' }}">
                    {{ $sale->paid ? 'Cash' : 'Debt' }}
                </span>
            </td>
            <td>
                {{ $sale->created_at->format('Y-m-d') }}
            </td>
        </tr>
        @endforeach
    </table>
</body>

</html>