<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Item Stock List')</title>
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
        <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Item Stock List')</h5>

        <div class="products">
            <table border="1">
                <thead>
                    <tr class="border-bottom">
                        <th>@lang('Item Name')</th>
                        <th>@lang('Last Month Stock')</th>
                        <th>@lang('Purchase')</th>
                        <th>@lang('Item Use')</th>
                        <th>@lang('Production Loss')</th>
                        <th>@lang('Current stock')</th>
                        <th>@lang('Stock Value')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        $totalamount = 0;
                    @endphp
                    @foreach ($itemswithcategories as $departmentId => $products)
                        @php
                            $departmentName = optional($products->first()->category)->name;
                            $amount = 0;
                        @endphp
                        <tr>
                            <td colspan="7" class="font-weight-bold text-primary text-start">
                                {{ $departmentName ?: 'No Category' }}
                            </td>
                        </tr>

                        @foreach ($products as $key => $product)
                            @php
                                $amount += $product->stock($product->id) * $product->price;
                                $totalamount += $product->stock($product->id) * $product->price;
                            @endphp
                            <tr class="product-row">
                                <td style="text-align: left"> {{ en2bn($i++) }} - {{ $product->name }}
                                </td>
                                <td style="text-align: center">
                                    {{ round($product->getopeningstock($product->id), 2) }}</td>
                                <td style="text-align: center">
                                    {{ $product->getpurchasevalue($product->id) }}</td>
                                <td style="text-align: center">
                                    {{ $product->getmakeproductionvalue($product->id) + $product->getproductppstock($product->id) + $product->getproductboxstock($product->id) + $product->getproductstrikerstock($product->id) }}
                                </td>
                                <td style="text-align: center">
                                    {{ $product->productionloss($product->id) }} </td>
                                <td style="text-align: center">
                                    {{ round($product->stock($product->id), 2) }}
                                </td>
                                <td>
                                    {{ round($product->stock($product->id) * $product->price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="6">@lang('Total')</th>
                            <th>{{ round($amount) }}</th>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th  colspan="6">@lang('Total')</th>
                        <th>{{ round($totalamount) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
