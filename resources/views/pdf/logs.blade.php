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
        # Logs for {{ $user->name }}
    </h3>
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
            @forelse ($logs as $log)
            @php
            $log->old_data = json_decode($log->old_data);
            $log->new_data = json_decode($log->new_data);
            @endphp
            <tr>
                <td class="col-1">
                    {{ $log->created_at  }}
                </td>
                <td class="col-1">
                    {{ $log->page }}
                </td>
                <td class="col-2 text-center">
                    {{ $log->action }}
                </td>
                <td>
                    @if (is_array($log->old_data))
                    @forelse ($log->old_data as $index => $data)
                    <div class=" {{ $log->old_data != '' && count($log->old_data) !=0 && $loop->index == 0 ? '' : 'mt-1' }}  {{ $log->old_data != '' && count($log->old_data) !=0 && $log->new_data !=[] && $log->new_data[$index] !== $data ? 'different' : '' }}">
                        {{ $data }}
                    </div>
                    @empty
                    <p>
                        {{ __('header.No Data') }}
                    </p>
                    @endforelse
                    @else
                    {{ $log->old_data }}
                    @endif
                </td>
                <td>
                    @if (is_array($log->new_data))
                    @forelse ($log->new_data as $index => $data)
                    <div class="{{ $log->old_data != '' && count($log->old_data) !=0 && $loop->index == 0 ? '' : 'mt-1' }}   {{ $log->old_data != '' &&  count($log->old_data) !=0 && $log->old_data !=[] && $log->old_data[$index] !== $data ? 'different' : '' }}">
                        {{ $data }}
                    </div>
                    @empty
                    <p>
                        {{ __('header.No Data') }}
                    </p>
                    @endforelse
                    @else
                    {{ $log->new_data }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    {{ __('header.No Data') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>