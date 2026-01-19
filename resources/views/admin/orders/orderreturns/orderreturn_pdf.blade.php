<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Order Return List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Order Return')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Order ID') </th>
                            <th>@lang('Customer')</th>
                            <th>@lang('QTY')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalqty = 0;
                        @endphp
                        @forelse($orderreturns as $item)
                        @php
                            $totalqty += $item->orderreturndetail->sum('qty');
                        @endphp
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ optional($item->order)->oid }}</td>
                                <td style="text-align: left;padding-left:10px"> {{ optional($item->customer)->name }}</td>
                                <td> {{ en2bn(optional($item->orderreturndetail)->sum('qty')) }}</td>
                                <td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($item->totalamount)) }}</td>
                                <td> {{ en2bn($item->created_at->format('d-m-Y')) }} </td>
                                <td> {{ optional($item->entryuser)->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">@lang('Total')</th>
                            <th>{{ en2bn(number_format($totalqty)) }}</th>
                            <th>{{ en2bn(number_format($orderreturns->sum('totalamount'))) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>
