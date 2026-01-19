<tbody>
    @php
        $total_quantity_value = 0;
        $total_price_value = 0;
        $total_amount_value = 0;
    @endphp
    @foreach ($data['purchases'] as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->category }}</td>
        <td>{{ $item->brand }}</td>
        <td>{{ $item->code }}/{{ $item->pid }}</td>
        <td>{{ $item->supplier_name }}</td>
        <td>{{ $item->reference_invoice_no }}</td>
        <td>{{ $item->date }}</td>
        <td>{{ number_format($item->quantity, 3) }}</td>
        <td>{{ number_format($item->price, 3) }}</td>
        <td>{{ number_format($item->totalamount, 3) }}</td>
    </tr>

    @php
        $total_quantity_value += $item->quantity;
        $total_price_value += $item->price;
        $total_amount_value += $item->totalamount;
    @endphp
    @endforeach

    <tr>
        <td colspan="7"></td>
        <td>{{ number_format($total_quantity_value, 3) }}</td>
        <td>{{ number_format($total_price_value, 3) }}</td>
        <td>{{ number_format($total_amount_value, 3) }}</td>
    </tr>
</tbody>
