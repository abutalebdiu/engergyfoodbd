<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Marketer Commission Payment History')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Marketer Commission Payment History')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Marketer')</th>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($marketerCommissionPayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->date }}</td>
                                <td> {{ optional($item->marketer)->name }} </td>
                                <td> {{ optional($item->marketercommission)->invoice_no }} </td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }} </td>
                                <td>{{ optional($item->entryuser)->name }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5"></th>
                            <th>Total</th>
                            <th>{{ en2bn(number_format($marketerCommissionPayments->sum('amount'), 2, '.', ',')) }}
                            </th>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>
