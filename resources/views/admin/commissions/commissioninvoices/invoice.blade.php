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

        .customer-info {
            margin: 0;
        }

        .col-md-3 {
            width: 20%;
            float: left;
        }

        .col-md-12 {
            width: 100%;
            float: left;
        }

        h6 {
            padding: 0;
            margin: 0;
            font-size: 11pt;
            font-weight: 400;
        }

        .card {
            padding: 2px;
            text-align: left;
        }

        .card-body {
            padding: 5px;
            box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
        }

        .text-success {
            color: green;
        }
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
            <table>
                <tr>
                    <th>@lang('Name')</th>
                    <td>:</td>
                    <td>{{ $commissioninvoice->customer?->name }}</td>
                    <th>@lang('Mobile')</th>
                    <td>:</td>
                    <td>{{ en2bn($commissioninvoice->customer?->mobile) }}</td>
                </tr>
                <tr>
                    <th>@lang('Address')</th>
                    <td>:</td>
                    <td>{{ $commissioninvoice->customer?->address }}</td>
                    <th>@lang('Commission')</th>
                    <td>:</td>
                    <td>{{ __($commissioninvoice->customer?->commission_type) }}</td>
                </tr>
            </table>
            <h5 style="text-align:center;padding:0;margin:5px">@lang('Commission Invoice')</h5>
        </div>


        @if (!empty($mergedData) && count($mergedData) > 0)
            <div class="table-responsive">
                <table border="1">
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

                        @php
                            $totalnetchallanamount = 0;
                            $totaldueamount = 0;
                            $returncommissiontotal = 0;
                            $continueDue = 0;
                            $isFirstOrder = true;
                        @endphp

                        @foreach ($mergedData as $key => $item)
                            @if ($item['type'] == 'order')
                                @php
                                    $order = $item['data'];

                                    // প্রথম order থেকে previous_due দিয়ে শুরু
                                    if ($isFirstOrder) {
                                        $continueDue = $order->previous_due;
                                        $isFirstOrder = false;
                                    }

                                    // order related totals
                                    $totalnetchallanamount += $order->sub_total - $order->return_amount;
                                    $totaldueamount +=
                                        $order->sub_total -
                                        $order->return_amount -
                                        $order->paid_amount -
                                        $order->commission;
                                    $returncommissiontotal += $order->orderreturn->sum('commission');

                                    // current order due = grand total - paid_amount
                                    $currentOrderDue = $order->grand_total - $order->paid_amount;

                                    // running continue due
                                    $continueDue += $currentOrderDue;
                                @endphp

                                <tr>
                                    <td>{{ en2bn(Date('d-m-Y', strtotime($order->date))) }}</td>
                                    <td>
                                        #{{ $order->oid }}
                                        <input type="hidden" name="order_id[]" value="{{ $order->id }}">
                                    </td>
                                    <td>{{ en2bn(number_format($order->previous_due, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($order->return_amount, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->net_amount, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->commission ?? 0, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->orderreturn->sum('commission'), 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->grand_total, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->paid_amount, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($order->grand_total - $order->paid_amount, 2, '.', ',')) }}
                                    </td>

                                    <td>{{ $order->commission_status }}</td>
                                    <td></td>

                                    <td class="fw-bold text-danger">
                                        {{ en2bn(number_format($continueDue, 2, '.', ',')) }}</td>
                                </tr>
                            @else
                                @php
                                    $payment = $item['data'];

                                    // payment দিলে continue due থেকে minus
                                    $continueDue -= $payment->amount;
                                @endphp

                                <tr class="table-info">
                                    <td>{{ en2bn(Date('d-m-Y', strtotime($payment->date))) }}</td>
                                    <td colspan="4">
                                        <span class="badge bg-success">@lang('Due Payment')</span>
                                    </td>
                                    <td colspan="5">@lang('Customer Due Payment')</td>
                                    <td colspan="2"> {{ $payment->created_at }} </td>
                                    <td><strong>{{ en2bn(number_format($payment->amount, 2, '.', ',')) }}</strong>
                                    </td>

                                    <td class="fw-bold text-danger">
                                        {{ en2bn(number_format($continueDue, 2, '.', ',')) }}</td>
                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr class="bg-secondary text-white">
                            <td>@lang('Total')</td>
                            <td></td>
                            <td></td>
                            <td>{{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}</td>
                            <td>{{ en2bn(number_format($orders->sum('return_amount'), 2, '.', ',')) }}
                            </td>
                            <input type="hidden" name="return_amount" value="{{ $orders->sum('return_amount') }}">
                            <input type="hidden" name="net_amount" value="{{ $orders->sum('net_amount') }}">
                            <input type="hidden" name="paid_amount" value="{{ $orders->sum('paid_amount') }}">
                            <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                            <td>{{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($orders->sum('grand_total') - $orders->sum('paid_amount'), 2, '.', ',')) }}
                            </td>
                            <td></td>
                            <td>{{ en2bn(number_format($commissioninvoice->customer_due_payment, 2, '.', ',')) }}
                            </td>
                            <td></td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <div class="summery" style="width: 45%;padding-top:10px">
            <table border="1">
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
                            @if ($last_month_due->count() > 0)
                                {{ en2bn(number_format($last_month_due->sum('amount'), 2, '.', ',')) }}
                            @else
                                {{ en2bn(number_format($commissioninvoice->customer?->opening_due, 2, '.', ',')) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Customer Due Payment')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($commissioninvoice->customer_due_payment, 2, '.', ',')) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Total Company/Customer Receivable')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($commissioninvoice->amount, 2, '.', ',')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="signature-detail">
            <table style="width: 100%;text-align:center;margin-top:60px">
                <tr>
                    <td><span style="border-top:1px solid #000;">@lang('Prepared By')</span></td>
                    <td><span style="border-top:1px solid #000;">@lang('Authority Signature')</span></td>
                    <td><span style="border-top:1px solid #000;">@lang('Receiver')</span></td>
                </tr>
            </table>
        </div>
        <div class="page-footer-notice" style="margin-top:10px;font-size:14px">
            <p style="text-align: center;margin-top:5%">বি:দ্র: বিক্রিত পণ্য ফেরত যোগ্য নয়</p>
        </div>
    </div>
</body>

</html>
