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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Department Group Report')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Total Received Qty')</th>
                            <th>@lang('Total Received Cost')</th>
                            <th>@lang('PP Cost')</th>
                            <th>@lang('Box Cost')</th>
                            <th>@lang('Striker Cost')</th>
                            <th>@lang('Total Cost')</th>
                            <th>@lang('Production Qty')</th>
                            <th>@lang('Production Gram')</th>
                            <th>@lang('Production Price')</th>
                            <th>@lang('Profit/Loss')</th>
                            <th>@lang('Profit/Loss') (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_r_qty = 0;
                            $total_r_cost = 0;
                            $total_pp__cost = 0;
                            $total_box__cost = 0;
                            $total_striker__cost = 0;
                            $total__cost = 0;
                            $total_p_qty = 0;
                            $total_p_gram = 0;
                            $total_p_cost = 0;
                            $total_profit_loss = 0;
                            $total_profit_loss_percentage = 0;
                        @endphp
                        @forelse($items as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item['name'] }} </td>
                                <td> {{ en2bn(number_format($item['total_received_qty'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_received_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_pp_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_box_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_striker_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['production_qty'], 2)) }} </td>
                                <td> {{ en2bn($item['production_gram']) }}  <br>   ({{ en2bn(number_format($item['production_gram']/1000,2))  }} KG)</td>
                                <td> {{ en2bn(number_format($item['production_price'], 2)) }} </td>
                                <td class="{{ $item['profit_or_loss'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ en2bn(number_format($item['profit_or_loss'], 2)) }} </td>
                                <td
                                    class="{{ $item['profit_or_loss_percentage'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ en2bn(number_format($item['profit_or_loss_percentage'], 2)) }}% </td>
                            </tr>

                            @php
                                $total_r_qty += $item['total_received_qty'];
                                $total_r_cost += $item['total_received_cost'];
                                $total_pp__cost += $item['total_pp_cost'];
                                $total_box__cost += $item['total_box_cost'];
                                $total_striker__cost += $item['total_striker_cost'];
                                $total__cost += $item['total_cost'];
                                $total_p_qty += $item['production_qty'];
                                $total_p_gram += $item['production_gram'];
                                $total_p_cost += $item['production_price'];
                                $total_profit_loss += $item['profit_or_loss'];
                                $total_profit_loss_percentage += $item['profit_or_loss_percentage'];
                            @endphp
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>@lang('Total')</th>
                            <th>{{ en2bn(number_format($total_r_qty, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_r_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_pp__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_box__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_striker__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_p_qty, 2)) }}</th>
                            <th>{{ en2bn($total_p_gram) }} গ্রাম <br> ({{ en2bn(number_format($total_p_gram/1000,2))  }} KG)</th>
                            <th>{{ en2bn(number_format($total_p_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_profit_loss, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_profit_loss_percentage / count($items), 2)) }}%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
