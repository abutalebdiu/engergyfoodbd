<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Order List')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 12px;
        }

        table td {
            font-size: 11px;
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Order List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL No')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('qty')</th>
                            <th>@lang('Sub Total')</th>
                            <th>@lang('Return Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Amount')</th>
                            <th>@lang('Previous Due')</th>
                            <th>@lang('Total Due Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalqty = 0; @endphp
                        @forelse($orders as $item)
                            @php $totalqty += $item->orderdetail->sum('qty'); @endphp
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                <td> {{ optional($item->customer)->name }} </td>
                                <td> {{ en2bn($item->orderdetail->sum('qty')) }}</td>
                                <td> {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->return_amount, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->paid_amount, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->grand_total - $item->paid_amount, 2, '.', ',')) }}
                                </td>
                                <td> {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2"></th>
                            <th>@lang('Total')</th>
                            <th>{{ en2bn($totalqty) }}</th>
                            <th>
                                {{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('return_amount'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                            </th>


                            <th>
                                {{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}
                            </th>

                            <th>
                                {{ en2bn(number_format($orders->sum('previous_due'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('customer_due'), 2, '.', ',')) }}
                            </th>

                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>

        </div>
    </div>
</body>

</html>
