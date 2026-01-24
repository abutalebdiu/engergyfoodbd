<thead>
    <tr>
        <th>@lang('SL')</th>
        <th>@lang('Date')</th>
        <th>@lang('Quotation Order Amount')</th>
        <th>@lang('Order Amount')</th>
        <th>@lang('Order Return Amount')</th>
        <th>@lang('Net Amounts')</th>
        
        <th>@lang('Sales Item Cost')</th>
        <th>@lang('PP Cost')</th>
        <th>@lang('Box Cost')</th>
        <th>@lang('Striker Cost')</th>
        <th>@lang('Total Sales Cost')</th>       
        <th>@lang('Sales Profit')</th>       
        
        <th>@lang('Customer Commission')</th>
        <th>@lang('Paid Amounts')</th>
        <th>@lang('Customer Due Payment')</th>
        <th>@lang('Marketer Commission')</th>
        <th>@lang('Product Damage')</th>
        <th>@lang('Customer Product Damage')</th>


        <th>@lang('Item Order')</th>
        <th>@lang('Item Orders Payment')</th>


        <th>@lang('Make Production Qty')</th>
        <th>@lang('Make Production Cost')</th>

        <th>@lang('Production PP Cost')</th>
        <th>@lang('Production Box Cost')</th>
        <th>@lang('Production Striket Cost')</th>

        <th>@lang('Total Production Cost')</th>

        <th>@lang('Daily Production Qty')</th>
        <th>@lang('Daily Production Amount')</th>
        <th>@lang('Daily Production Profit')</th>
    </tr>
</thead>
<tbody>
    @foreach ($datas['archives'] as $key => $data)
        <tr>
            <td>{{ en2bn($loop->iteration) }}</td>
            <td>{{ en2bn($data['date']) }}</td>
            <td>{{ en2bn(number_format(isset($data['quotation_order_amount']) ? $data['quotation_order_amount'] : 0, 2)) }}
            </td>
            <td>{{ en2bn(number_format(isset($data['order_amount']) ? $data['order_amount'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['order_return_amount']) ? $data['order_return_amount'] : 0, 2)) }}
            </td>
            <td>{{ en2bn(number_format(isset($data['net_amount']) ? $data['net_amount'] : 0, 2)) }}</td>
            
            <td>{{ en2bn(number_format(isset($data['sales_cost']) ? $data['sales_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['pp_cost']) ? $data['pp_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['box_cost']) ? $data['box_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['striker_cost']) ? $data['striker_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['datewish_total_sales_cost']) ? $data['datewish_total_sales_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['sales_profit']) ? $data['sales_profit'] : 0, 2)) }}</td>
            
            <td>{{ en2bn(number_format(isset($data['customer_commission']) ? $data['customer_commission'] : 0, 2)) }}
            </td>
            <td>{{ en2bn(number_format(isset($data['paid_amount']) ? $data['paid_amount'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['customer_due_payment']) ? $data['customer_due_payment'] : 0, 2)) }}
            </td>
            <td>{{ en2bn(number_format(isset($data['marketer_commission']) ? $data['marketer_commission'] : 0, 2)) }}
            </td>
            <td>{{ en2bn(number_format(isset($data['product_damage']) ? $data['product_damage'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['customer_product_damage']) ? $data['customer_product_damage'] : 0, 2)) }}
            </td>
            <td>{{ en2bn(number_format(isset($data['item_order']) ? $data['item_order'] : 0, 0)) }}</td>
            <td>{{ en2bn(number_format(isset($data['item_order_payment']) ? $data['item_order_payment'] : 0, 0)) }}</td>


            <td>{{ en2bn(number_format(isset($data['make_production_qty']) ? $data['make_production_qty'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['make_production_cost']) ? $data['make_production_cost'] : 0, 2)) }}</td>

            <td>{{ en2bn(number_format(isset($data['daily_production_pp_cost']) ? $data['daily_production_pp_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['daily_production_box_cost']) ? $data['daily_production_box_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['daily_production_striker_cost']) ? $data['daily_production_striker_cost'] : 0, 2)) }}</td>

            <td>{{ en2bn(number_format(isset($data['daily_make_production_cost']) ? $data['daily_make_production_cost'] : 0, 2)) }}</td>

            <td>{{ en2bn(number_format(isset($data['daily_production_qty']) ? $data['daily_production_qty'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['daily_production_cost']) ? $data['daily_production_cost'] : 0, 2)) }}</td>
            <td>{{ en2bn(number_format(isset($data['daily_production_profit']) ? $data['daily_production_profit'] : 0, 2)) }}</td>

        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <th>@lang('Total')</th>
        <th></th>
        <th>{{ en2bn(number_format(isset($datas['total_quotation_order_amount']) ? $datas['total_quotation_order_amount'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_order_amount']) ? $datas['total_order_amount'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_order_return_amount']) ? $datas['total_order_return_amount'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_net_amount']) ? $datas['total_net_amount'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_sales_cost']) ? $datas['total_sales_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_pp_cost']) ? $datas['total_pp_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_box_cost']) ? $datas['total_box_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_striker_cost']) ? $datas['total_striker_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_datewish_total_sales_cost']) ? $datas['total_datewish_total_sales_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_sales_profit']) ? $datas['total_sales_profit'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_customer_commission']) ? $datas['total_customer_commission'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_paid_amount']) ? $datas['total_paid_amount'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_customer_due_payment']) ? $datas['total_customer_due_payment'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_marketer_commission']) ? $datas['total_marketer_commission'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_product_damage']) ? $datas['total_product_damage'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_customer_product_damage']) ? $datas['total_customer_product_damage'] : 0, 2)) }}
        </th>
        <th>{{ en2bn(number_format(isset($datas['total_item_order']) ? $datas['total_item_order'] : 0, 0)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_item_order_payments']) ? $datas['total_item_order_payments'] : 0, 0)) }}</th>

        <th>{{ en2bn(number_format(isset($datas['total_make_production_qty']) ? $datas['total_make_production_qty'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_make_production_cost']) ? $datas['total_make_production_cost'] : 0, 2)) }}</th>

        <th>{{ en2bn(number_format(isset($datas['total_daily_production_pp_cost']) ? $datas['total_daily_production_pp_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_daily_production_box_cost']) ? $datas['total_daily_production_box_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_daily_production_striker_cost']) ? $datas['total_daily_production_striker_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_production_cost']) ? $datas['total_production_cost'] : 0, 2)) }}</th>

        <th>{{ en2bn(number_format(isset($datas['total_daily_production_qty']) ? $datas['total_daily_production_qty'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_daily_production_cost']) ? $datas['total_daily_production_cost'] : 0, 2)) }}</th>
        <th>{{ en2bn(number_format(isset($datas['total_production_profit']) ? $datas['total_production_profit'] : 0, 2)) }}</th>
    </tr>
</tfoot>
