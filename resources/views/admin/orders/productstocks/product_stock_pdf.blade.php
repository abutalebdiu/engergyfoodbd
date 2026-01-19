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
                    <tr class="border-bottom">
                        <th>@lang('Product')</th>
                        <th>@lang('Weight')</th>
                        <th>@lang('Sale Price')</th>
                        <th style="width:8%">@lang('Last Month Stock')</th>
                        <th>@lang('Production')</th>
                        <th>@lang('Sales')</th>
                        <th>@lang('Return')</th>
                        <th style="width:8%">@lang('Stock Damage')</th>
                        <th style="width:8%">@lang('Customer Damage')</th>
                        <th style="width:8%">@lang('Current stock')</th>
                        <th>@lang('Physcial Stock')</th>
                        <th>@lang('Difference')</th>
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
                            <td colspan="12" class="font-weight-bold text-primary text-start">
                                {{ $departmentName ?: 'No Department' }}
                            </td>
                        </tr>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td style="text-align: left;">{{ en2bn($i++) }} -  {{ $product->name }} </td>
                                 <td style="text-align: center;">{{ $product->weight }}</td>
                                 <td style="text-align: center;">{{ $product->sale_price }}</td>
                                <td style="text-align: center;">{{ $product->getopeningstock($product->id) }}</td>
                                <td style="text-align: center;">{{ $product->getproductionvalue($product->id) }}</td>
                                <td style="text-align: center;">{{ $product->getsalevalue($product->id) }}</td>
                                <td style="text-align: center;">{{ $product->getcustomerorderreturn($product->id) }}</td>
                                <td style="text-align: center;">{{ $product->getproductdamage($product->id) }}</td>
                                <td style="text-align: center;">{{ $product->getcustomerproductdamage($product->id) }}</td>
                                <td style="text-align: center;">{{ $product->getStock($product->id) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
