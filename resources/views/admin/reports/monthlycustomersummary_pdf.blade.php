<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Daily Report')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 16px;
        }

        table td {
            font-size: 15px;
        }
    </style>
</head>

<body>
    <div>
        <div class="wrapper">
            <div class="print-header" style="text-align: center;margin-bottom:15px">
                <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                <p style="margin: 0;padding:0">{{ $general->address }}</p>
                <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
            </div>
            <h5 style="text-align: center;margin: 0;padding:0;border-bottom:1px solid #000">@lang('Monthly Reports')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                @if ($searching == 'Yes')
                    <p>@lang('Month Name') : {{ $monthname }} - {{ $yearname }}</p>
                    <table  border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>@lang('ID')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Address')</th>
                            <th>@lang('Commission Status')</th>
                            <th>@lang('Previous Due')</th>
                            <th>@lang('Challan Amount')</th>
                            <th>@lang('Return Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Return Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Collection')</th>
                            <th>@lang('মোট আদায়')</th>
                            <th>@lang('Total Due Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $t_last_month_due = 0;
                            $t_order_amount = 0;
                            $t_return_amount = 0;
                            $t_net_amount = 0;
                            $t_commission = 0;
                            $t_return_commission = 0;
                            $t_grand_total = 0;
                            $t_paid_amount = 0;
                            $t_due_collection = 0;
                            $t_total_due_amount = 0;
                        @endphp

                        @forelse($rows as $row)
                            @php
                                $t_last_month_due += $row['last_month_due'];
                                $t_order_amount += $row['order_amount'];
                                $t_return_amount += $row['return_amount'];
                                $t_net_amount += $row['net_amount'];
                                $t_commission += $row['commission'];
                                $t_return_commission += $row['return_commission'];
                                $t_grand_total += $row['grand_total'];
                                $t_paid_amount += $row['paid_amount'];
                                $t_due_collection += $row['due_collection'];
                                $t_total_due_amount += $row['total_due_amount'];
                            @endphp

                            <tr>
                                <td>{{ $row['uid'] }}</td>
                                <td class="text-start">{{ $row['name'] }}</td>
                                <td class="text-start">{{ $row['address'] }}</td>
                                <td>{{ $row['commission_type'] }}</td>

                                <td class="text-end">{{ en2bn(number_format($row['last_month_due'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['order_amount'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['return_amount'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['net_amount'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['commission'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['return_commission'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['grand_total'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['paid_amount'], 2)) }}</td>
                                <td class="text-end">{{ en2bn(number_format($row['due_collection'], 2)) }}</td>
                                <td class="text-end">
                                    {{ en2bn(number_format($row['paid_amount'] + $row['due_collection'], 2)) }}
                                </td>
                                <td class="text-end text-danger fw-bold">
                                    {{ en2bn(number_format($row['total_due_amount'], 2)) }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="15" class="text-center">No Data Found</td>
                            </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                        <tr class="bg-secondary text-white">
                            <th colspan="4" class="text-end">@lang('Total')</th>

                            <th class="text-end">{{ en2bn(number_format($t_last_month_due, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_order_amount, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_return_amount, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_net_amount, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_commission, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_return_commission, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_grand_total, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_paid_amount, 2)) }}</th>
                            <th class="text-end">{{ en2bn(number_format($t_due_collection, 2)) }}</th>
                            <th class="text-end">
                                {{ en2bn(number_format($t_paid_amount + $t_due_collection, 2)) }}
                            </th>
                            <th class="text-end">
                                {{ en2bn(number_format($t_total_due_amount, 2)) }}
                            </th>
                        </tr>
                    </tfoot>

                </table>
                @endif

            </div>
        </div>
</body>

</html>
