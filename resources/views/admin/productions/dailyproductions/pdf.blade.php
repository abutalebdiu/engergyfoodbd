<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Daily Productions')  ({{ $date }})</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 14px;
        }

        table td {
            font-size: 13px;
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
            <h4 style="text-align: center;margin: 0;padding:0">@lang('Daily Productions')</h4>
            <pstyle="text-align: center;margin: 0;padding:0">Production Date: {{ $date }}</p> 
            <div class="product-detail">
               <table border="1">
                        <thead>
                            <tr class="border-bottom">
                                <th>@lang('SL No')</th>
                                <th>@lang('Product')</th>
                                <th>@lang('Unit Price')</th>
                                <th>@lang('Quantity')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('PP Cost')</th>
                                <th>@lang('Box Cost')</th>
                                <th>@lang('Striker Cost')</th>
                                <th>@lang('Weight')(গ্রাম)</th>
                                <th>@lang('Total Weight')(গ্রাম)</th>
                                <th>@lang('Total Weight (KG)')</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @php
                                $totalqty = 0;
                                $totalmainamount = 0;
                                $totalpp_cost = 0;
                                $totalbox_cost = 0;
                                $totalstriker_cost = 0;
                                $total_weight_gram = 0;
                                $total_weight_kg = 0;
                            @endphp
                    
                            @foreach ($dailyproductions as $departmentId => $productions)
                                @php
                                    // reset department-wise subtotal
                                    $dept_total_amount = 0;
                                    $dept_total_weight_gram = 0;
                                    $dept_total_weight_kg = 0;
                                @endphp
                    
                                <tr>
                                    <td colspan="11" style="font-weight: bold; text-align: left;">
                                        {{ optional($productions->first()->product)->department->name ?? 'Unknown Department' }}
                                    </td>
                                </tr>
                    
                                @foreach ($productions as $key => $dailyproduction)
                                    @php
                                        $line_amount = $dailyproduction->qty * $dailyproduction->product->sale_price;
                                        $line_weight_gram = optional($dailyproduction->product)->weight_gram * $dailyproduction->qty;
                                        $line_weight_kg = $line_weight_gram / 1000;
                    
                                        $dept_total_amount += $line_amount;
                                        $dept_total_weight_gram += $line_weight_gram;
                                        $dept_total_weight_kg += $line_weight_kg;
                                    @endphp
                    
                                    <tr>
                                        <td>{{ en2bn($loop->iteration) }}</td>
                                        <td style="text-align: left">{{ optional($dailyproduction->product)->name }}</td>
                                        <td>{{ en2bn($dailyproduction->product->sale_price) }}</td>
                                        <td>{{ en2bn($dailyproduction->qty) }}</td>
                                        <td>{{ en2bn(number_format($line_amount, 2, '.', ',')) }}</td>
                                        <td>{{ en2bn($dailyproduction->pp_cost) }}</td>
                                        <td>{{ en2bn($dailyproduction->box_cost) }}</td>
                                        <td>{{ en2bn($dailyproduction->striker_cost) }}</td>
                                        <td>{{ en2bn(optional($dailyproduction->product)->weight_gram) }}</td>
                                        <td>{{ en2bn($line_weight_gram) }}</td>
                                        <td>{{ en2bn(number_format($line_weight_kg, 2)) }}</td>
                                    </tr>
                                @endforeach
                    
                                {{-- Department subtotal --}}
                                <tr style="font-weight: bold; background-color: #f8f9fa;">
                                    <th colspan="2">@lang('Total for ')
                                        {{ optional($productions->first()->product)->department->name ?? 'Unknown Department' }}
                                    </th>
                                    <td></td>
                                    <td>{{ en2bn(number_format($productions->sum('qty'), 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($dept_total_amount, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($productions->sum('pp_cost'), 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($productions->sum('box_cost'), 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($productions->sum('striker_cost'), 2, '.', ',')) }}</td>
                                    <td></td>
                                    <td>{{ en2bn(number_format($dept_total_weight_gram, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($dept_total_weight_kg, 2, '.', ',')) }}</td>
                                </tr>
                    
                                @php
                                    // accumulate overall totals
                                    $totalqty += $productions->sum('qty');
                                    $totalmainamount += $dept_total_amount;
                                    $totalpp_cost += $productions->sum('pp_cost');
                                    $totalbox_cost += $productions->sum('box_cost');
                                    $totalstriker_cost += $productions->sum('striker_cost');
                                    $total_weight_gram += $dept_total_weight_gram;
                                    $total_weight_kg += $dept_total_weight_kg;
                                @endphp
                            @endforeach
                        </tbody>
                    
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #e9ecef;">
                                <th colspan="2">@lang('Grand Total')</th>
                                <th></th>
                                <th>{{ en2bn(number_format($totalqty, 0, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalmainamount, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalpp_cost, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalbox_cost, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalstriker_cost, 2, '.', ',')) }}</th>
                                <th></th>
                                <th>{{ en2bn(number_format($total_weight_gram, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($total_weight_kg, 2, '.', ',')) }}</th>
                            </tr>
                        </tfoot>
                    </table>
            </div>
            
        </div>
    </div>
</body>

</html>
