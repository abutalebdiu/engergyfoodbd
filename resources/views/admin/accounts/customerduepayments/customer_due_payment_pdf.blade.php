<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Order List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Customer Due Payment List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Entry User')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customerduepayments as $item)
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y',strtotime($item->date))) }}</td>
                                <td style="text-align: left;padding-left:10px"> {{ optional($item->customer)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($item->amount, 2, '.', ',')) }}</td>
                                <td> {{ optional($item->entryuser)->name }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">@lang('Total')</th>
                            <th>{{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>
