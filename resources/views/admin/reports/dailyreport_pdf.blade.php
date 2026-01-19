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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Daily Reports')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                 @if ($searching == 'Yes')
                <div class="row mt-4">
                    <div class="col-12">
                        <p class=" mt-5">@lang('Date'): @if (isset($date))
                                {{ en2bn(Date('d-m-Y', strtotime($date))) }}
                            @endif
                        </p>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th colspan="3">Credit</th>
                                    <th colspan="3">Debit</th>
                                </tr>
                                <tr>
                                    <th>@lang('SL No')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('SL No')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ en2bn('1') }}</td>
                                    <td style="text-align:left;padding-left:10px">Sales Order Amount</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($salesamount, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('1') }}</td>
                                    <td style="text-align:left;padding-left:10px">Item Payment</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($itempayments, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('2') }}</td>
                                    <td style="text-align:left;padding-left:10px"> Sales Payment</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($salepayments, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('2') }}</td>
                                    <td style="text-align:left;padding-left:10px">Expense Payment</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($expensepayments, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('3') }}</td>
                                    <td style="text-align:left;padding-left:10px"> Sales Return</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($returnamounts, 2, '.', ',')) }}</td>

                                    <td>{{ en2bn('3') }}</td>
                                    <td style="text-align:left;padding-left:10px">Salary Advance</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($salaryadvance, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('3') }}</td>
                                    <td style="text-align:left;padding-left:10px"> Customer Payment Receive</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customerduepayment, 2, '.', ',')) }}</td>

                                    <td>{{ en2bn('3') }}</td>
                                    <td style="text-align:left;padding-left:10px">Supplier Due Payment</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($supplierduepaymnet, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('4') }}</td>
                                    <td style="text-align:left;padding-left:10px">Deposit</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($deposit, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('4') }}</td>
                                    <td style="text-align:left;padding-left:10px">Salary Payment</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($salarypayment, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('5') }}</td>
                                    <td style="text-align:left;padding-left:10px">Item Order Amount</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($itemorderamount, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('5') }}</td>
                                    <td style="text-align:left;padding-left:10px">Employee Loan</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($loans, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('6') }}</td>
                                    <td style="text-align:left;padding-left:10px">Item Return Amount</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($itemreturns, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('6') }}</td>
                                    <td style="text-align:left;padding-left:10px">Office Loan Payment</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($officialloanpayment, 2, '.', ',')) }}</td>

                                </tr>
                                <tr>
                                    <td>{{ en2bn('6') }}</td>
                                    <td style="text-align:left;padding-left:10px">Office Loan</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($officeloans, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('6') }}</td>
                                    <td style="text-align:left;padding-left:10px">Over Time Allowance</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($overtimeallowance, 2, '.', ',')) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ en2bn('7') }}</td>
                                    <td style="text-align:left;padding-left:10px">Expense</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($expense, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn('7') }}</td>
                                    <td style="text-align:left;padding-left:10px"> Withdrawal </td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($overtimeallowance, 2, '.', ',')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            </div>
        </div>
</body>

</html>
