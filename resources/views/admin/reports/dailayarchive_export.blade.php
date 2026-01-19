<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Department Group Report')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Day Wise Order Summery / Daily Archive')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Quotation QTY')</th>
                            <th>@lang('Quotation Amount')</th>
                            <th>@lang('Order Qty')</th>
                            <th>@lang('Order Amount')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Order Due')</th>
                            <th>@lang('Customer Due Payment')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Product Return Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas['archives'] as $key => $data)
                            <tr>
                                <td>{{ en2bn($loop->iteration) }}</td>
                                <td>{{ en2bn($data['date']) }}</td>
                                <td>{{ en2bn(number_format($data['quotation_qty'], 0)) }}</td>
                                <td>{{ en2bn(number_format($data['quotation_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['order_qty'], 0)) }}</td>
                                <td>{{ en2bn(number_format($data['order_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['paid_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['order_due'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['customer_due_payment'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['commission_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['order_return_amount'], 2)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('Total')</th>
                            <th></th>
                            <th>{{ en2bn(number_format($datas['total_quotation_qty'], 0)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_quotation_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_qty'], 0)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_paid_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_due'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_customer_due_payment'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_commission_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_return_amount'], 2)) }}</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
</body>

</html>
