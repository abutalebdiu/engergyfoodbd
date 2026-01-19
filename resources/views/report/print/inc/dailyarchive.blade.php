<tbody>
    @foreach ($datas['archives'] as $key => $data)
        <tr>
            <td>{{ en2bn($loop->iteration) }}</td>
            <td>{{ en2bn($data['date']) }}</td>
            <td>{{ en2bn(number_format($data['quotation_qty'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['quotation_amount'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['order_qty'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['order_amount'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['paid_amount'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['order_due'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['customer_due_payment'], 2)) }}</td>
        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <th>@lang('Total')</th>
        <th></th>
        <th>{{ en2bn(number_format($datas['total_quotation_qty'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_quotation_amount'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_order_qty'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_order_amount'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_paid_amount'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_order_due'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_customer_due_payment'], 2)) }}</th>
    </tr>
</tfoot>
