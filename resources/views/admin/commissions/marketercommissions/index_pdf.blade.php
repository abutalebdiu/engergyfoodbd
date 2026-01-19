<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Marketer Commission List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Marketer Commission List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Marketer')</th>
                            <th>@lang('Previous Due Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Payment')</th>
                            <th>@lang('Total Due Amount')</th>
                            <th>@lang('Payable Amount (Marketer Commssion)')</th>
                            <th>@lang('Marketer Commssion Paid')</th>
                            <th>@lang('Marketer Commssion Unpaid')</th>
                            <th>@lang('Overall Due')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $key => $invoice)
                            <tr>
                                <td>
                                    {{ $invoice->invoice_no }}
                                </td>
                                <td>
                                    {{ Date('d-M-Y', strtotime($invoice->date)) }}
                                </td>
                                <td>
                                    {{ $invoice->marketer?->name }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->previous_due, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->net_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->paid_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->customer_due_payment, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->total_due_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->payable_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->marketercommissionpayment->sum('amount'), 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->payable_amount - $invoice->marketercommissionpayment->sum('amount'), 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->total_due_amount - $invoice->payable_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ $invoice->payment_status }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
