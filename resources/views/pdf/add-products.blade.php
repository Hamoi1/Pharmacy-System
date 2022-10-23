<x-pdf-layout :user="$user">
    <table id="sales">
        <tr>
            <th>
                Product Name
            </th>
            <th>
                Barcode
            </th>
            <th>
                Purches Price
            </th>
            <th>
                Sale Price
            </th>
            <th>
                Quantity
            </th>
            <th>
                Create At
            </th>
        </tr>
        @foreach($products as $product)
        <tr>
            <td>
                {{ $product->name }}
            </td>
            <td>
                {{ $product->barcode }}
            </td>
            <td>
                {{ number_format($product->purches_price,0)  }} IQD
            </td>
            <td>
                {{ number_format($product->sale_price,0)  }} IQD
            </td>
            <td>
                {{ $product->quantity }}
            </td>
            <td>
                {{ $product->created_at->format('Y-m-d') }}
            </td>
        </tr>
        @endforeach
    </table>

</x-pdf-layout>