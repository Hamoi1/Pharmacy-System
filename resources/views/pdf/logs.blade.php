<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        #logs {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #logs td,
        #logs th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #logs th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }

        .bold {
            font-weight: bold;
        }

        .different {
            background: #212F3D !important;
            color: white;
            padding: 5px 10px !important;
            border-radius: 10px !important;
        }

        .user {
            font-size: 20px !important;
            font-weight: bold !important;
            color: #212F3D !important;
            padding: 10px 0 !important;
            margin: 20px 0 !important;
        }
    </style>
</head>

<body>
    <h3 class="user">
        Logs for {{ $user->name }}
    </h3>
    @if ($file)
    <table id="logs">
        <thead>
            <tr>
                <th>
                    Date
                </th>
                <th>
                    Page
                </th>
                <th>
                    Action
                </th>
                <th>
                    Old Data
                </th>
                <th>
                    New Data
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($file as $key => $item)
            @php
            $oldData = $item[3] !=null || $item[3] !="" ? explode(',', $item[3]) : []; // change to array by a explode function
            $newData = $item[4] !=null || $item[4] !="" ? explode(',', $item[4]) : [];
            @endphp
            <tr>
                <td>
                    {{ $item[0] }}
                </td>
                <td>
                    {{ $item[1] }}
                </td>
                <td class="">
                    {{ $item[2] }}
                </td>
                <td>
                    @forelse ($oldData as $index => $data)
                    <div class=" {{ count($oldData) !=0 && $loop->index == 0 ? '' : 'mt-1' }}  {{ count($oldData) !=0 && $newData !=[] && $newData[$index] !== $data ? 'different' : '' }}">
                        {{ $data }}
                    </div>
                    @empty
                    <p>
                        {{ __('header.No Data') }}
                    </p>
                    @endforelse
                </td>
                <td>
                    @forelse ($newData as $index => $data)
                    <div class="{{ count($oldData) !=0 && $loop->index == 0 ? '' : 'mt-1' }}   {{  count($oldData) !=0 && $oldData !=[] && $oldData[$index] !== $data ? 'different' : '' }}">
                        {{ $data }}
                    </div>
                    @empty
                    <p>
                        No Data
                    </p>
                    @endforelse
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>
        No Data
    </p>
    @endif
</body>

</html>