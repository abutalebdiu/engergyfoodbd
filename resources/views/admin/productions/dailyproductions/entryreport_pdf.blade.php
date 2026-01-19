<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Daily Production Entry Reports')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Daily Production Entry Reports')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                @if ($searching == 'Yes')
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('Sl')</th>
                            <th>@lang('Product Name')</th>
                            <th>@lang('Weight')</th>
                            @foreach($dates as $date)
                                <th>{{ $date }}</th>
                            @endforeach
                            <th>@lang('Total Qty')</th> <!-- Total per product -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            @php
                                $totalPerProduct = 0;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align:left">{{ $product->name }}</td>
                                <td>{{ $product->weight }}</td>
                                @foreach($dates as $date)
                                    @php
                                        $qty = isset($productions[$product->id]) 
                                            ? $productions[$product->id]->firstWhere('date', $date)?->total_qty ?? 0 
                                            : 0;
                                        $totalPerProduct += $qty;
                                    @endphp
                                    <td>{{ $qty }}</td>
                                @endforeach
                                <td><strong>{{ $totalPerProduct }}</strong></td> <!-- Right-side total qty -->
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">@lang('Total')</th>
                            @foreach($dates as $date)
                                <th>{{ $dateWiseSum[$date] ?? 0 }}</th>
                            @endforeach
                            <th>
                                <strong>{{ number_format(array_sum($dateWiseSum),0) }}</strong>
                            </th> <!-- Grand total of all quantities -->
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
