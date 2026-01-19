<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Item Order Payment List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Item Order Payment List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('No')</th>
                            <th>@lang('Supplier Name')</th>
                            <th>@lang('Item No')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Mother Account')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordersupplierpayments as $data)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $data->tnx_no }} </td>
                                <td style="text-align: left"> {{ optional($data->supplier)->name }} </td>
                                <td> {{ optional($data->item)->iid }} </td>
                                <td> {{ en2bn(number_format($data->amount, 2)) }}</td>
                                <td> {{ optional($data->paymentmethod)->name }}</td>
                                <td> {{ optional($data->account)->title }}</td>
                                <td> {{ $data->note }}</td>
                                <td> {{ $data->date }}</td>
                                <td>{{ optional($data->entryuser)->name }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($data->status) }} btn-sm">{{ $data->status }}</span>
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
                            <th colspan="3"></th>
                            <th>Total</th>
                            <th>{{ en2bn(number_format($ordersupplierpayments->sum('amount'), 2)) }}</th>
                            <td colspan="7"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>
