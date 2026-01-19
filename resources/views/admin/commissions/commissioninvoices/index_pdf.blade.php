<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Customer Commission List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Customer Commission List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Last Month Due')</th>
                            <th>@lang('Order Amount')</th>
                            <th>@lang('Return Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Customer Due Payment')</th>
                            <th>@lang('Commission Type')</th>
                            <th>@lang('Payable Commission')</th>
                            <th>@lang('Receivable Amount')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissioninvoices as $key => $invoice)
                            <tr>
                                <td>
                                    {{ $invoice->invoice_id ?? $invoice->id }}
                                </td>
                                <td>
                                    {{ optional($invoice->month)->name }} - {{ $invoice->year }}
                                </td>
                                <td style="text-align:left">
                                    {{ en2bn($invoice->customer?->uid) }} - {{ $invoice->customer?->name }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->last_month_due,2,'.',',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->order_amount,2,'.',',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->return_amount,2,'.',',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->net_amount,2,'.',',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->commission,2,'.',',')) }}
                                </td>
                                <td>
                                    @if($invoice->customer->commission_type == "Daily")
                                         {{ en2bn(number_format($invoice->net_amount - $invoice->commission,2,'.',',')) }}
                                    @else
                                         {{ en2bn(number_format($invoice->net_amount,2,'.',',')) }}
                                    @endif
                                </td>

                                <td>
                                    {{ en2bn(number_format($invoice->paid_amount,2,'.',',')) }}
                                </td>
                                 <td>
                                    {{ en2bn(number_format($invoice->customer_due_payment,2,'.',',')) }}
                                </td>
                                <td>
                                    {{ $invoice->customer?->commission_type }}
                                </td>

                                <td>
                                    {{ en2bn(number_format($invoice->commission_amount,2,'.',',')) }}
                                </td>

                                <td>
                                    {{ en2bn(number_format($invoice->receivable_amount,2,'.',',')) }}
                                </td>
                                <td>
                                    {{ $invoice->payment_status }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">@lang('Total')</th>
                            <th>{{ en2bn(number_format($commissioninvoices->sum('last_month_due'),2,'.',',')) }}</th>
                            <th colspan="9"></th>
                            <th>{{ en2bn(number_format($commissioninvoices->sum('amount'),2,'.',',')) }}</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="13">@lang('Different')</th>
                            <th>{{ en2bn(number_format($commissioninvoices->sum('amount') - $commissioninvoices->sum('last_month_due'), 2, '.', ',')) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
