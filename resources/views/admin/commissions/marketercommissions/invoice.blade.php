<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Marketer Commission')</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 20pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .customer-info {}
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="print-header"
            style="text-align: center;margin-bottom:1px;padding-bottom:5px;border-bottom:1px solid #000">
            <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
            <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
        </div>

        <div class="customer-info">
            <p>
                <b>@lang('Name')</b> : {{ $marketercommission->marketer?->name }}, <br>
                <b>@lang('Mobile')</b> : {{ $marketercommission->marketer?->mobile }}, <br>
                <b>@lang('Address')</b> : {{ $marketercommission->marketer?->address }} <br>
                <b>@lang('Commission')</b> : {{ en2bn($marketercommission->marketer?->amount) }}%
            </p>
        </div>
        <h4 style="text-align:center;padding:0;margin:0">@lang('Marketer Commission Invoice')</h4>
        <div class="product-detail">
            @if ($marketercommission->orders)
                <table border="1">
                    <thead>
                        <tr>

                            <th>@lang('SL No')</th>
                            <th>@lang('OID')</th>
                            <th>@lang('Date')</th>
                            <th style="width: 150px">@lang('Customer')</th>
                            <th>@lang('qty')</th>
                            <th>@lang('Sub Total')</th>
                            <th>@lang('Return Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Amount')</th>
                            <th>@lang('Commission Status')</th>
                            <th>@lang('Previous Due')</th>
                            <th>@lang('Total Due Amount')</th>
                            <th>@lang('Marketer Commission')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalqty = 0;
                            $orders = $marketercommission->orders;
                        @endphp
                        @forelse($orders as $item)
                            @php $totalqty += $item->orderdetail->sum('qty'); @endphp
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ $item->oid }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                <td> {{ optional($item->customer)->name }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn($item->orderdetail->sum('qty')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->return_amount, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->paid_amount, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->order_due, 2, '.', ',')) }}</td>
                                <td> {{ $item->commission_status }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->marketer_commission, 2, '.', ',')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3"></th>
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
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('previous_due'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('customer_due'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($orders->sum('marketer_commission'), 2, '.', ',')) }}
                            </th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->

                <br>


                <div style="width:40%">
                    <table style="width: 100%;font-size:11px" border="1">
                        <tbody>
                            <tr>
                                <th style="text-align: left">Customers Previous Due Amount</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->previous_due, 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left">Total Order Amount</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->net_amount, 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left">Total Paid Amount</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->paid_amount, 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left">Total Customer Due Payment</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->customer_due_payment, 2, '.', ',')) }}
                                </td>
                            </tr>
                            <tr>
                                <th style="text-align: left">Total Due</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->total_due_amount, 2, '.', ',')) }}
                                </td>
                            </tr>
                            <tr>
                                <th style="text-align: left">Marketer Commission</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->payable_amount, 2, '.', ',')) }}
                                </td>
                            </tr>
                            <tr>
                                <th style="text-align: left">Overall Due</th>
                                <td style="text-align: right;padding-left:10px">
                                    {{ en2bn(number_format($marketercommission->total_due_amount - $marketercommission->payable_amount, 2, '.', ',')) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="signature-detail">
            <table style="width: 100%;text-align:center;margin-top:10%;">
                <tbody>
                    <tr>
                        <td><span style="border-top:1px solid #000;">@lang('Prepared By')</span></td>
                        <td><span style="border-top:1px solid #000;">@lang('Authority Signature')</span></td>
                        <td><span style="border-top:1px solid #000;">@lang('Receiver')</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="page-footer-notice" style="margin-top:5%;font-size:14px">
            <p style="text-align: center;margin-top:5%">বি:দ্র: বিক্রিত পণ্য ফেরত যোগ্য নয়</p>
        </div>
    </div>
</body>

</html>
