<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Balance Sheets')</title>
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
            <h4 style="text-align: center;margin: 0;padding:0">@lang('Balance Sheets')</h4>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
            <table border="1">
                <thead>
                    <tr>
                        <th colspan="3">Assets</th>
                        <th colspan="3">Liabilities</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td style="text-align:left;padding-left:10px">Office Assets</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($assetsamount, 2)) }}</td>
                        <td>1</td>
                        <td style="text-align:left;padding-left:10px">Investment Equity</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($liabilities, 2)) }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align:left;padding-left:10px">Asset Expenses</td>
                        <td  style="text-align: right;padding-right:10px">{{ en2bn(number_format($assetexpenses, 2)) }}</td>
                        <td>2</td>
                        <td style="text-align:left;padding-left:10px">Office Payable (Salary)</td>
                        <td  style="text-align: right;padding-right:10px">{{ en2bn(number_format($salarypayable, 2)) }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align:left;padding-left:10px">Salary Advanced</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($salaryadvances, 2)) }}</td>
                        <td>3</td>
                        <td style="text-align:left;padding-left:10px">Supplier Payable</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($payables, 2)) }}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px">Items Stock Value</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($itemstockamount, 2)) }}</td>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px"> Monthly Expenses </td>
                        <td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($monthlyexpense, 2)) }} </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td style="text-align:left;padding-left:10px">Products Stock Value</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($prductstockamount, 2)) }}</td>
                        <td>5</td>
                        <td style="text-align:left;padding-left:10px">  কারখানা ভাড়া </td>
                        <td style="text-align: right;padding-right:10px">  {{ en2bn(number_format($factoryrent, 2)) }} </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td style="text-align:left;padding-left:10px">Employee Loan Receivable</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($employeeloan, 2)) }}</td>
                        <td>6</td>
                        <td style="text-align:left;padding-left:10px"> জামানত </td>
                        <td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($jamanot, 2)) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align:left;padding-left:10px">Total Assets</th>
                        <th style="text-align: right;padding-right:10px">{{ en2bn(number_format($totalassets, 2)) }}</th>
                        <th colspan="2">Total Liabilities</th>
                        <th style="text-align: right;padding-right:10px">{{ en2bn(number_format($totalliabilities, 2)) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5"> Different</th>
                        <th>{{ en2bn(number_format($differentvalue, 2)) }}</th>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
</body>

</html>
