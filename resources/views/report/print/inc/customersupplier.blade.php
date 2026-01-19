<tbody>
    @php
        $total_purchase_value = 0;
        $total_purchase_return_value = 0;
        $total_sale_value = 0;
        $total_sale_return_value = 0;
        $opening_balance_value = 0;
        $total_due_value = 0;
    @endphp
    @foreach ($data['all_contacts'] as $contact)
    <tr>
        <td>{{ $contact['name'] }}</td>
        <td>{{ number_format($contact['total_purchase'], 3) }}</td>
        <td>{{ number_format($contact['total_sale'], 3) }}</td>
        <td>{{ number_format($contact['total_purchase_return'], 3) }}</td>
        <td>{{ number_format($contact['total_sale_return'], 3) }}</td>
        <td>{{ number_format($contact['opening_balance'], 3) }}</td>
        <td>{{ number_format($contact['total_due'], 3) }}</td>
    </tr>

    @php
        $total_purchase_value += $contact['total_purchase'];
        $total_purchase_return_value += $contact['total_purchase_return'];
        $total_sale_value += $contact['total_sale'];
        $total_sale_return_value += $contact['total_sale_return'];
        $opening_balance_value += $contact['opening_balance'];
        $total_due_value += $contact['total_due'];
    @endphp
    @endforeach

    <tr>
        <td></td>
        <td>{{ number_format($total_purchase_value, 3) }}</td>
        <td>{{ number_format($total_purchase_return_value, 3) }}</td>
        <td>{{ number_format($total_sale_value, 3) }}</td>
        <td>{{ number_format($total_sale_return_value, 3) }}</td>
        <td>{{ number_format($opening_balance_value, 3) }}</td>
        <td>{{ number_format($total_due_value, 3) }}</td>
    </tr>
</tbody>
