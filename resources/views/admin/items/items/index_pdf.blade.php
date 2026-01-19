<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Items List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Items List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1" style="width:100%">
                     <thead>
                        <tr>                           
                            <th style="width:5%">@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Unit')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Stock')</th>
                            <th>@lang('Value')</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php
                            $i = 1;
                            $totalqty = 0;
                            $totalvalue = 0;
                        @endphp
                        
                        @foreach($itemsgroupes as $items)
                           @php
                                $categoryName = optional($items->first()->category)->name;
                            @endphp
                        
                           <tr>
                                <td style="text-align: left" colspan="7">
                                    {{ $categoryName ?: 'No Category' }}
                                </td>
                            </tr>
                            
                         @php
                            $totalgroupqty   = 0;
                            $totalgroupvalue = 0;
                        @endphp
                       
                        @forelse($items as $item)
                        
                                @php
                                    $totalqty   += $item->stock($item->id);
                                    $totalvalue += $item->stock($item->id) * $item->price;
                                    
                                    $totalgroupqty   += $item->stock($item->id);
                                    $totalgroupvalue += $item->stock($item->id) * $item->price;
                                @endphp
                                
                                
                            <tr>                               
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td class="text-start"> {{ $item->name }} </td>
                                <td> {{ optional($item->category)->name }} </td>
                                <td> {{ $item->unit?->name ?? 'N/A' }}</td>
                                <td> {{ en2bn($item->price ?? '0.00') }}</td>
                                <td> {{ en2bn(number_format($item->stock($item->id) ?? '0.00'),2,'.',',') }}</td>
                                <td> {{ en2bn(number_format($item->stock($item->id) ?? '0.00' * $item->price, 2, '.', ',')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse
                        
                            <tr>
                                <th colspan="5">@lang('Total')</th>
                                <th>{{ en2bn(number_format($totalgroupqty, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalgroupvalue, 2, '.', ',')) }}</th>
                             </tr>
                             
                             
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">@lang('Total')</th>
                            <th>{{ en2bn(number_format($totalqty, 2, '.', ',')) }}</th>
                            <th>{{ en2bn(number_format($totalvalue, 2, '.', ',')) }}</th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>
</html>
