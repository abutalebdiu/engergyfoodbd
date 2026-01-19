<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @lang('Order List') </title>
</head>

<body>
    <div>
        <div class="wrapper">
            <div>
                <table style="text-align: center">
                    <tr>
                        <th colspan="13"></th>
                    </tr>
                    <tr>
                        <th colspan="13" style="text-align: center">{{ $general->site_name }}</th>
                    </tr>
                    <tr>
                        <th colspan="13" style="text-align: center">{{ $general->address }}</th>
                    </tr>
                    <tr>
                        <th colspan="13" style="text-align: center">অফিস: {{ $general->phone }}, হেল্প
                            লাইন:{{ $general->mobile }}</th>
                    </tr>
                    <tr>
                        <th colspan="13"></th>
                    </tr>
                    <tr>
                        <th colspan="13" style="text-align: center">
                            @lang('Order List')
                        </th>
                    </tr>
                    <tr>
                        <th colspan="13" style="text-align: right">
                            @lang('Date'): {{ Date('d-m-Y') }}
                        </th>
                    </tr>
                </table>
            </div>


            <div>
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
                                <td> {{ en2bn(number_format($item->grand_total - $item->paid_amount, 2, '.', ',')) }}</td>

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
