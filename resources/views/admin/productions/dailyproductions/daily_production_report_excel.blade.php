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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Department Group Report')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('Sl')</th>
                            <th>@lang('Product Name')</th>
                            <th>@lang('Yeast')</th>
                            @foreach ($items as $item)
                                <th class="text-start">{{ $loop->iteration }} - {{ $item->name }}</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Total')</th>
                                <th>@lang('Receive Qty')</th>
                                <th>@lang('Cost')</th>
                            @endforeach
                            <th>@lang('Total Yeast Cost')</th>
                            <th>@lang('Receive Item QTY')</th>
                            <th>@lang('Receive Item Cost')</th>
                            <th>@lang('PP Cost')</th>
                            <th>@lang('Box Cost')</th>
                            <th>@lang('Striker Cost')</th>
                            <th>@lang('Total Cost')</th>
                            <th>@lang('Production QTY')</th>
                            <th>@lang('Production Amount')</th>
                            <th>@lang('Profit/Loss')</th>
                            <th>@lang('Profit/Loss %')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalItemQty = [];
                            $totalItemCost = [];
                            $grandTotalProductCost = 0;

                            $receiveqty = 0;
                            $receiveamount = 0;

                            $dailyproductionqty = 0;
                            $dailyproductionamount = 0;

                        @endphp
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $product->name }}</td>
                                <td>{{ en2bn($product->yeast) }}</td>
                                @php
                                    $totalProductCost = 0;
                                @endphp
                                @foreach ($items as $item)
                                    @php
                                        $recipe = App\Models\Product\ProductRecipe::where('product_id', $product->id)
                                            ->where('item_id', $item->id)
                                            ->first();
                                        $qty = $recipe ? $recipe->qty : 0;
                                        $cost = $qty * $item->price;

                                        // Calculate totals per item
                                        $totalItemQty[$item->id] = ($totalItemQty[$item->id] ?? 0) + $qty;
                                        $totalItemCost[$item->id] = ($totalItemCost[$item->id] ?? 0) + $cost;

                                        // Calculate total cost per product
                                        $totalProductCost += $cost;
                                    @endphp
                                    <td>{{ en2bn($qty) }}</td>
                                    <td>{{ en2bn($item->price) }}</td>
                                    <td>{{ en2bn(number_format($cost, 2)) }}</td>
                                    @if ($key == 0)
                                        <td rowspan="{{ $products->count() }}">
                                            {{ en2bn($item->makeproductionqtysum($start_date, $end_date, $item->id, $department_id)) }}
                                            @php
                                                $receiveqty += $item->makeproductionqtysum(
                                                    $start_date,
                                                    $end_date,
                                                    $item->id,
                                                    $department_id,
                                                );
                                            @endphp
                                        </td>
                                    @endif
                                    @if ($key == 0)
                                        <td rowspan="{{ $products->count() }}">
                                            {{ en2bn(round($item->price * $item->makeproductionqtysum($start_date, $end_date, $item->id, $department_id), 2)) }}

                                            @php
                                                $receiveamount +=
                                                    $item->price *
                                                    $item->makeproductionqtysum(
                                                        $start_date,
                                                        $end_date,
                                                        $item->id,
                                                        $department_id,
                                                    );
                                            @endphp
                                        </td>
                                    @endif
                                @endforeach
                                <td>{{ en2bn(number_format($totalProductCost, 2)) }}</td>
                                @php
                                    $grandTotalProductCost += $totalProductCost;
                                @endphp
                                @php
                                    $dailyproductions = App\Models\DailyProduction::where('product_id', $product->id)
                                        ->whereBetween('date', [$start_date, $end_date])
                                        ->get();

                                    $dailyproductionqty += $dailyproductions->sum('qty');
                                    $dailyproductionamount += $dailyproductions->sum('qty') * $product->sale_price;
                                @endphp

                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($total_received_qty, 2)) }}
                                    </td>
                                @endif


                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($total_received_cost, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($total_pp_cost, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($total_box_cost, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($total_striker_cost, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($total_cost, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($production_qty, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($production_price, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($profit_or_loss, 2)) }}
                                    </td>
                                @endif
                                @if ($key == 0)
                                    <td rowspan="{{ $products->count() }}">
                                        {{ en2bn(number_format($profit_or_loss_percentage, 2)) }}%
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">@lang('Total')</th>
                            @foreach ($items as $item)
                                <th>{{ en2bn($totalItemQty[$item->id] ?? 0) }}</th>
                                <th></th>
                                <th>{{ en2bn(number_format($totalItemCost[$item->id] ?? 0, 2)) }}</th>
                                <td></td>
                                <td></td>
                            @endforeach
                            <th></th>
                            <th>{{ en2bn(number_format($total_received_qty, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_received_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_pp_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_box_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_striker_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($production_qty, 2)) }}</th>
                            <th>{{ en2bn(number_format($production_price, 2)) }}</th>
                            <th class="{{ $profit_or_loss > 0 ? 'text-success' : 'text-danger' }}">
                                {{ en2bn(number_format($profit_or_loss, 2)) }}
                            </th>
                            <th class="{{ $profit_or_loss_percentage > 0 ? 'text-success' : 'text-danger' }}">
                                {{ en2bn(number_format($profit_or_loss_percentage, 2)) }}%
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
