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

        .products {
            float: left;
            width: 100%;
            margin-left: 2px;
        }
    </style>
</head>

<body>
    <div>
        <div class="print-header" style="text-align: center;margin-bottom:1px">
            <h4 style="margin: 0;padding:0;font-size:12pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
        </div>
        <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Products List')</h5>
        @php $i = 1; @endphp

        <div class="products">
            <table border="1" style="width: 100%">
                <thead>
                    <tr class="border-bottom">
                        <th style="width: 10%">@lang('SL No')</th>
                        <th style="width: 55%">@lang('Item Name')</th>
                        <th style="width: 15%">@lang('Quantity')</th>
                        <th style="width: 10%">@lang('Unit')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productss as $key => $product)
                        <tr style="background-color: #ddd">
                            <td colspan="4"> @lang('Name') : {{ $product->name }} / @lang('Yeast') :
                                {{ en2bn($product->yeast) }} {{ $product->yeast_unit }}</td>
                        </tr>
                        @foreach ($product->productrecipe as $recipe)
                            <tr>
                                <td>{{ $i++ }} - {{ $loop->iteration }}</td>
                                <td>{{ optional($recipe->item)->name }}</td>
                                <td>{{ en2bn($recipe->qty) }}</td>
                                <td>{{ optional($recipe->unit)->name }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
