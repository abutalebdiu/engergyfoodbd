<thead>
    <tr>
        <th>@lang('SL')</th>
        <th>@lang('Date')</th>
        <th>@lang('Sales')</th>
        <th>@lang('Commission')</th>
        <th>@lang('Order Return')</th>
        <th>@lang('Product Damage')</th>
        <th>@lang('Customer Product Damage')</th>
        <th>@lang('Item Order')</th>
        <th>@lang('Supplier Payment')</th>
        <th>@lang('Supplier Due Payment')</th>
        <th>@lang('Supplier Due')</th>
        <th>@lang('Total Due')</th>
        <th>@lang('Office Loan')</th>
        <th>@lang('Employee Advance')</th>
        <th>@lang('Office Expense')</th>
        <th>@lang('Office Expense Payment')</th>
    </tr>
</thead>
<tbody>
    @foreach ($datas['archives'] as $key => $data)
        <tr>
            <td>{{ en2bn($loop->iteration) }}</td>
            <td>{{ en2bn($data['date']) }}</td>
            <td>{{ en2bn(number_format($data['sales'], 0)) }}</td>
            <td>{{ en2bn(number_format($data['commission'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['order_return'], 0)) }}</td>
            <td>{{ en2bn(number_format($data['damage'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['customer_damage_amount'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['purchase_product'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['supplier_payment'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['supplier_due_payment'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['supplier_due'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['total_due'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['office_loan'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['employee_advance'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['office_expense'], 2)) }}</td>
            <td>{{ en2bn(number_format($data['office_expense_payment'], 2)) }}</td>
        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <th>@lang('Total')</th>
        <th></th>
        <th>{{ en2bn(number_format($datas['total_sales'], 0)) }}</th>
        <th>{{ en2bn(number_format($datas['total_commission'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_order_return'], 0)) }}</th>
        <th>{{ en2bn(number_format($datas['total_damage'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_customer_damage'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_purchase'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_supplier_payment'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_supplier_due_payment'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_supplier_due'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_due_amount'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_office_loan'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_employee_advance'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_office_expense'], 2)) }}</th>
        <th>{{ en2bn(number_format($datas['total_office_expense_payment'], 2)) }}</th>
    </tr>
</tfoot>
