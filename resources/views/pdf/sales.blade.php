<x-pdf-layout :user="$user">
    @if ($sales)
    <p>
        Total slae price is :
        {{ number_format($sales->sum('total'),0) }} IQD
    </p>
    @endif
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
        @foreach($sales as $sale)
        <tr>
            <td>
                {{ $sale->invoice }}
            </td>
            <td>
                {{ number_format($sale->total,2,',',',')  }} $
            </td>
            <td>
                {{ $sale->discount != null ?  number_format($sale->discount,2,',',',') . '$' : __('header.Not-discount') }}
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

</x-pdf-layout>