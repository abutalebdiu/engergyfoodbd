<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Distribution Commission Product Wise')</title>
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
            <h5 style="text-align: center;margin: 5px 0;padding:0">@lang('Distribution Commission Product Wise')</h5>
             <div class="customer-info">
                <table>
                    <tbody>
                        <tr>
                            <th style="text-align: left;width:9%">সরবরাহকারী নাম:</th>
                            <td style="text-align: left;width:1%">:</td>
                            <td style="text-align: left;width:40%">{{ $distributor->name }}</td>
                            <th style="text-align: left;width:9%">আইডি:</th>
                            <td style="text-align: left;width:1%">:</td>
                            <td style="text-align: left;width:40%">{{ en2bn($distributor->id) }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left;width:9%">@lang('Mobile')</th>
                            <td style="text-align: left;width:1%">:</td>
                            <td style="text-align: left;width:40%">{{ $distributor->mobile }}</td>
                        
                            <th style="text-align: left;width:9%">ঠীকানা: </th>
                            <td style="text-align: left;width:1%">:</td>
                            <td style="text-align: left;width:40%">{{ $distributor->address }}</td>
                           
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                   <table border="1">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Type')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $currentDept = null; 
                                    $sl = 1;
                                @endphp
                                @foreach ($products as $product)
                                    @if ($currentDept !== $product->department_id)
                                        @php $currentDept = $product->department_id; @endphp
                                        <tr class="table-primary">
                                            <td colspan="5" style="text-align:left;padding:3px 10px">
                                                <strong>{{ $product->department->name ?? 'No Department' }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                        
                                    <tr>
                                        <td>{{ en2bn($sl++) }}</td>
                                        <td style="text-align:left;padding:3px 10px">{{ $product->name }}  </td>
                                        <td>{{ en2bn($product->distributorCommission?->price ?? $product->sale_price) }}</td>
                                        <td>{{ en2bn($product->distributorCommission?->amount ?? 0) }}</td>
                                        <td>{{ $product->distributorCommission?->type   }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
            </div>
            
        </div>
    </div>
</body>

</html>
