<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Employee Loan List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Employee Loan List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Interest')</th>
                            <th>@lang('Total Amount')</th>
                            <th>@lang('Monthly Installment')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $item)
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td> {{ optional($item->employee)->name }} </td>
                                <td> {{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ en2bn(number_format($item->amount,2,'.',',')) }}</td>
                                <td> {{ en2bn(number_format($item->interest,2,'.',',')) }}</td>
                                <td> {{ en2bn(number_format($item->total_amount,2,'.',',')) }}</td>
                                <td> {{ number_format($item->monthly_settlement) }}</td>
                                <td> {{ $item->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">@lang('Total')</th>
                            <th>{{ en2bn(number_format($loans->sum('amount'),2,'.',',')) }}</th>
                            <th>{{ en2bn(number_format($loans->sum('interest'),2,'.',',')) }}</th>
                            <th>{{ en2bn(number_format($loans->sum('total_amount'),2,'.',',')) }}</th>
                            <th colspan="5"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>
