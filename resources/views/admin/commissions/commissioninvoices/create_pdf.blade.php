<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Commission Invoice')</title>
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

        @if ($searching == 'Yes')
            <div class="row">
                <div class="pb-3 col-12 col-md-12">
                    <h5 style="margin:0;padding:0">@lang('Customer Info')</h5>

                    <table style="width:50%">
                        <tr>
                            <th style="text-align:left">@lang('ID')</th>
                            <td>:</td>
                            <td>{{ en2bn($findcustomer->uid) }}</td>
                            <th style="text-align:left">@lang('Name')</th>
                            <td>:</td>
                            <td>{{ $findcustomer->name }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left">@lang('Mobile')</th>
                            <td>:</td>
                            <td>{{ $findcustomer->mobile }}</td>
                            <th style="text-align:left">@lang('Address')</th>
                            <td>:</td>
                            <td>{{ $findcustomer->address }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left">@lang('Commission Type')</th>
                            <td>:</td>
                            <td>{{ __($findcustomer->commission_type) }}</td>
                        </tr>
                    </table>

                    @if (!empty($mergedData) && count($mergedData) > 0)
                        <div class="table-responsive">
                            <table border="1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Order ID')</th>
                                        <th>@lang('Previous Due')</th>
                                        <th>@lang('Challan Amount')</th>
                                        <th>@lang('Return Amount')</th>
                                        <th>@lang('Net Amount')</th>
                                        <th>@lang('Commission')</th>
                                        <th>@lang('Return Commission')</th>
                                        <th>@lang('Grand Total')</th>
                                        <th>@lang('Paid Amount')</th>
                                        <th>@lang('Due Total')</th>
                                        <th>@lang('Commission Status')</th>
                                        <th>@lang('Due Collection')</th>
                                        <th>@lang('Total Due Amount')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <th colspan="13" style="text-align:left">@lang('Last Month Due')</th>
                                        <td style="color: red">{{ en2bn(number_format($lastmonthdueamount, 2)) }}</td>
                                    </tr>
                                    @php
                                        // Start from LAST MONTH DUE / OPENING DUE
                                        $continueDue = $lastmonthdueamount;
                                        $returncommissiontotal = 0;
                                    @endphp

                                    @foreach ($mergedData as $item)
                                        {{-- ================= ORDER ROW ================= --}}
                                        @if ($item['type'] == 'order')
                                            @php
                                                $order = $item['data'];

                                                $currentOrderDue = $order->grand_total - $order->paid_amount;
                                                $continueDue += $currentOrderDue;

                                                $returncommissiontotal += $order->orderreturn->sum('commission');
                                            @endphp

                                            <tr>
                                                <td>{{ en2bn(date('d-m-Y', strtotime($order->date))) }}</td>

                                                <td>
                                                    #{{ $order->oid }}
                                                    <input type="hidden" name="order_id[]"
                                                        value="{{ $order->id }}">
                                                </td>

                                                <td>{{ en2bn(number_format($order->previous_due, 2)) }}</td>

                                                <td>{{ en2bn(number_format($order->sub_total, 2)) }}</td>

                                                <td>{{ en2bn(number_format($order->return_amount, 2)) }}</td>

                                                <td>{{ en2bn(number_format($order->net_amount, 2)) }}</td>

                                                <td>{{ en2bn(number_format($order->commission ?? 0, 2)) }}</td>

                                                <td>{{ en2bn(number_format($order->orderreturn->sum('commission'), 2)) }}
                                                </td>

                                                <td>{{ en2bn(number_format($order->grand_total, 2)) }}</td>

                                                <td>{{ en2bn(number_format($order->paid_amount, 2)) }}</td>

                                                <td>{{ en2bn(number_format($currentOrderDue, 2)) }}</td>

                                                <td>{{ $order->commission_status }}</td>

                                                <td></td>

                                                <td class="fw-bold text-danger">
                                                    {{ en2bn(number_format($continueDue, 2)) }}
                                                </td>
                                            </tr>

                                            {{-- ================= PAYMENT ROW ================= --}}
                                        @else
                                            @php
                                                $payment = $item['data'];
                                                $previousDue = $continueDue;
                                                $continueDue -= $payment->amount;
                                            @endphp

                                            <tr class="table-info">
                                                <td>{{ en2bn(date('d-m-Y', strtotime($payment->date))) }}</td>

                                                <td></td>

                                                {{-- Previous Due --}}
                                                <td class="fw-bold">
                                                    {{ en2bn(number_format($previousDue, 2)) }}
                                                </td>

                                                <td colspan="4">
                                                    <span class="badge bg-success">@lang('Due Payment')</span>
                                                </td>

                                                <td colspan="5">@lang('Customer Due Payment')</td>

                                                <td>
                                                    <strong>{{ en2bn(number_format($payment->amount, 2)) }}</strong>
                                                </td>

                                                <td class="fw-bold text-danger">
                                                    {{ en2bn(number_format($continueDue, 2)) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-secondary text-white">
                                        <td>@lang('Total')</td>
                                        <td></td>
                                        <td></td>

                                        <td>{{ en2bn(number_format($orders->sum('sub_total'), 2)) }}</td>
                                        <td>{{ en2bn(number_format($orders->sum('return_amount'), 2)) }}</td>
                                        <td>{{ en2bn(number_format($orders->sum('net_amount'), 2)) }}</td>
                                        <td>{{ en2bn(number_format($orders->sum('commission'), 2)) }}</td>
                                        <td>{{ en2bn(number_format($returncommissiontotal, 2)) }}</td>
                                        <td>{{ en2bn(number_format($orders->sum('grand_total'), 2)) }}</td>
                                        <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2)) }}</td>
                                        <td>{{ en2bn(number_format($orders->sum('order_due'), 2)) }}</td>
                                        <td></td>
                                        <td>{{ en2bn(number_format($customerduepayments->sum('amount'), 2)) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 ">
                    <br>
                    <table border="1" style="width:40%">
                        <tbody>
                            <tr>
                                <th style="text-align:left">@lang('Total Order Amount')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Current Month Commission')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Return Commission')</th>
                                <td style="text-align:right;padding-right:10px"
                                    style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('After Commission Total')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}</td>

                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Total Order Paid Amount')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}</td>
                            </tr>

                            <tr>
                                <th style="text-align:left">@lang('Current Month Due')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                            </tr>

                            <tr>
                                <th style="text-align:left">@lang('Last Month Due')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($lastmonthdueamount, 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Customer Due Payment')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Total Company/Customer Receivable')</th>
                                <td style="text-align:right;padding-right:10px">
                                    {{ en2bn(number_format($last_customer_total_due, 2, '.', ',')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="page-footer-notice" style="margin-top:10%;font-size:14px">
                <p style="text-align: center;margin-top:5%">বি:দ্র: বিক্রিত পণ্য ফেরত যোগ্য নয়</p>
            </div>
        @endif

    </div>
</body>

</html>
