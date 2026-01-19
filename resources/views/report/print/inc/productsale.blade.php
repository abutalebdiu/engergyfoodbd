<tbody>
    @if(!empty($data['orders']))
        @php
            $total_quantity_value = 0;
            $total_price_value = 0;
            $total_discount_amount_value = 0;
            $total_vat_amount_value = 0;
            $total_ait_amount_value = 0;
            $total_amount_value = 0;
        @endphp
        @foreach ($data['orders'] as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->code }}/{{ $item->oid }}</td>
            <td>{{ $item->customer_name }}</td>
            <td>{{ $item->contact }}</td>
            <td>{{ $item->reference_id }}</td>
            <td>{{ $item->date }}</td>
            <td>{{ number_format($item->quantity, 3) }}</td>
            <td>{{ number_format($item->price, 3) }}</td>
            <td>{{ number_format($item->discount_amount, 3) }}</td>
            <td>{{ number_format($item->vat_amount, 3) }}</td>
            <td>{{ number_format($item->ait_amount, 3) }}</td>
            <td>{{ number_format($item->totalamount, 3) }}</td>
            <td>{{ $item->payment_method }}</td>
        </tr>

        @php
            $total_quantity_value += $item->quantity;
            $total_price_value += $item->price;
            $total_discount_amount_value += $item->discount_amount;
            $total_vat_amount_value += $item->vat_amount;
            $total_ait_amount_value += $item->ait_amount;
            $total_amount_value += $item->totalamount;
        @endphp
        @endforeach

        <tr>
            <td colspan="6"></td>
            <td>{{ number_format($total_quantity_value, 3) }}</td>
            <td>{{ number_format($total_price_value, 3) }}</td>
            <td>{{ number_format($total_discount_amount_value, 3) }}</td>
            <td>{{ number_format($total_vat_amount_value, 3) }}</td>
            <td>{{ number_format($total_ait_amount_value, 3) }}</td>
            <td>{{ number_format($total_amount_value, 3) }}</td>
            <td></td>
        </tr>
    @endif
</tbody>
