<tbody>
    @php
        $total_purchase_value = 0;
        $total_purchase_return_value = 0;
        $total_sale_value = 0;
        $total_sale_return_value = 0;
        $opening_balance_value = 0;
        $total_due_value = 0;
    @endphp

    @foreach ($data['registers'] as $item)
    <tr>
        <td>{{ $item['register_open_date_time'] }}</td>
        <td>{{ $item['register_closed_date_time'] }}</td>
        <td>{{ $item['name'] }}</td>
        <td>{{ number_format($item['total_purchase'], 3) }}</td>
        <td>{{ number_format($item['total_purchase_return'], 3) }}</td>
        <td>{{ number_format($item['total_sale'], 3) }}</td>
        <td>{{ number_format($item['total_sale_return'], 3) }}</td>
        <td>{{ number_format($item['opening_balance'], 3) }}</td>
        <td>{{ number_format($item['total_due'], 3) }}</td>
    </tr>

    @php
        $total_purchase_value += $item['total_purchase'];
        $total_purchase_return_value += $item['total_purchase_return'];
        $total_sale_value += $item['total_sale'];
        $total_sale_return_value += $item['total_sale_return'];
        $opening_balance_value += $item['opening_balance'];
        $total_due_value += $item['total_due'];
    @endphp
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ number_format($total_purchase_value, 3) }}</td>
        <td>{{ number_format($total_purchase_return_value, 3) }}</td>
        <td>{{ number_format($total_sale_value, 3) }}</td>
        <td>{{ number_format($total_sale_return_value, 3) }}</td>
        <td>{{ number_format($opening_balance_value, 3) }}</td>
        <td>{{ number_format($total_due_value, 3) }}</td>
    </tr>
</tbody>
