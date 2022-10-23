<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
    {{ $slot }}

</body>

</html>