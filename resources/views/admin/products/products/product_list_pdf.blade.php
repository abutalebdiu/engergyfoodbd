<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Products List')</title>
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
            font-size: 14pt;
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
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div>
        <div class="print-header" style="text-align: center;margin-bottom:1px">
            <h3 style="margin: 0;padding:0;font-size:16pt">{{ $general->site_name }}</h3>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
        </div>
        <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Products List')</h5>

        <div class="products">
            <table border="1" style="width: 100%">
                <thead>
                    <tr class="border-bottom">
                        <th style="width: 10%">@lang('SL No')</th>
                        <th style="width: 45%">@lang('Product')</th>
                        <th style="width: 15%">@lang('Weight')</th>
                        <th style="width: 10%">@lang('Sale Price')</th>
                        <th style="width: 10%">@lang('Store/Shop Price')</th>
                        <th style="width: 10%">@lang('Retail Price')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($productswithgroupes as $departmentId => $products)
                        @php
                            $departmentName = optional($products->first()->department)->name;
                        @endphp
                        <tr>
                            <td colspan="6" class="font-weight-bold text-primary text-start">
                                {{ $departmentName ?: 'No Department' }}
                            </td>
                        </tr>
                        @foreach ($products->where('status', 'Active') as $key => $product)
                            <tr>
                                <td>{{ en2bn($i++) }} </td>
                                <td style="text-align:left;padding-left:5px"> {{ $product->name }} </td>
                                <td style="text-align:left;padding-left:5px"> {{ $product->weight }} </td>
                                <td style="text-align:center"> {{ en2bn($product->sale_price ?? 0) }} </td>
                                <td style="text-align:center"> {{ en2bn($product->shop_price ?? 0) }} </td>
                                <td style="text-align:center"> {{ en2bn($product->retail_price ?? 0) }} </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
