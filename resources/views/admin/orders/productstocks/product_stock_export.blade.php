<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Products Stock List')</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 0pt 30pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div>
        <div class="print-header" style="text-align: center;margin-bottom:1px">
            <h4 style="margin: 0;padding:0;font-size:12pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
        </div>
        <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Products Stock List')</h5>
        <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
        <div class="products">
            <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('Last Month Stock')</th>
                            <th>@lang('Production')</th>
                            <th>@lang('Sales')</th>
                            <th>@lang('Returns')</th>
                            <th>@lang('Stock Damage')</th>
                            <th>@lang('Customer Damage')</th>
                            <th>@lang('Get Stock')</th>
                            <th>@lang('Get Stock Value')</th>
                            <th>@lang('Physical QTY')</th>
                            <th>@lang('Physical Stock Value')</th>
                            <th>@lang('Settlement QTY')</th>
                            <th>@lang('Settlement Value')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $phystockvalue = 0;
                            $getstockvalue = 0;
                        @endphp
                        @forelse($productstocks as $item)
                        
                        @php
                            $phystockvalue += $item->physical_stock * $item->product->sale_price;
                            $getstockvalue += $item->current_stock * $item->product->sale_price;
                        @endphp
                          
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                <td style="text-align: left"> {{ optional($item->month)->name }}</td>
                                <td style="text-align: left"> {{ optional($item->product)->name }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->last_month_stock, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->production, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->sales, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->order_return, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->damage, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->customer_damage, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->current_stock, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->current_stock * $item->product->sale_price, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->physical_stock, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->physical_stock * $item->product->sale_price, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->qty, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->total_value, 2, '.', ',')) }}</td>
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
                            <th colspan="4">@lang('Total')</th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('last_month_stock'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('production'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('sales'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('order_return'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('damage'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('customer_damage'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('current_stock'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($getstockvalue, 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('physical_stock'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($phystockvalue, 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('qty'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('total_value'), 2, '.', ',')) }} </th>
                            <th> </th>
                        </tr>
                    </tfoot>
            </table><!-- table end -->
        </div>

    </div>
</body>

</html>
