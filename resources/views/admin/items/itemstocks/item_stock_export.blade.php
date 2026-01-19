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
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Item Name')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Last Month Stock')</th>
                            <th>@lang('Purchase')</th>
                            <th>@lang('Make Production')</th>
                            <th>@lang('Production Loss')</th>
                            <th>@lang('Get Stock')</th>
                            <th>@lang('Get Stock Value')</th>
                            <th>@lang('Physical QTY')</th>
                            <th>@lang('Physical Value')</th>
                            <th>@lang('Settlement QTY')</th>
                            <th>@lang('Settlement Value')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php
                            $grandTotalStock = 0;
                            $grandTotalValue = 0;
                            
                            $grandTotalPhyStock = 0;
                            $grandTotalPhyValue = 0;
                            
                            $grandTotalSettStock = 0;
                            $grandTotalSettValue = 0;
                        @endphp
                        @forelse($itemstocks as $category_id => $items)
                            <tr class="bg-secondary">
                                <td colspan="14" class="text-start  text-white"><strong>{{ optional($items->first()->item->category)->name }}</strong></td>
                            </tr>
                        
                            @php
                                $categoryTotalStock = $items->sum('current_stock');
                                $categoryTotalValue = $items->sum(fn($item) => $item->current_stock * $item->item->price);
                                
                                $grandTotalStock += $categoryTotalStock;
                                $grandTotalValue += $categoryTotalValue;
                                
                                
                                $categoryTotalPhyStock = $items->sum('physical_stock');
                                $categoryTotalPhyValue = $items->sum(fn($item) => $item->physical_stock * $item->item->price);
                                
                                $grandTotalPhyStock += $categoryTotalPhyStock;
                                $grandTotalPhyValue += $categoryTotalPhyValue;
                                
                                
                                $categoryTotalSettStock = $items->sum('qty');
                                $categoryTotalSettValue = $items->sum(fn($item) => $item->qty * $item->item->price);
                                
                                $grandTotalSettStock += $categoryTotalSettStock;
                                $grandTotalSettValue += $categoryTotalSettValue;
                            @endphp
                        
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($item->item)->name }}</td>
                                    <td>{{ optional($item->month)->name }}</td>
                                    <td>{{ number_format($item->last_month_stock, 2) }}</td>
                                    <td>{{ number_format($item->purchase, 2) }}</td>
                                    <td>{{ number_format($item->make_production, 2) }}</td>
                                    <td>{{ number_format($item->production_loss, 2) }}</td>
                                    <td>{{ number_format($item->current_stock, 2) }}</td>
                                    <td>{{ number_format($item->current_stock * $item->item->price, 2) }}</td>
                                    <td>{{ number_format($item->physical_stock, 2) }}</td>
                                    <td>{{ number_format($item->physical_stock * $item->item->price, 2) }}</td>
                                    <td>{{ number_format($item->qty, 2) }}</td>
                                    <td>{{ number_format($item->total_value, 2) }}</td>
                                    <td>{{ optional($item->entryuser)->name }}</td>
                                </tr>
                            @endforeach
                        
                            {{-- Category Total --}}
                            <tr class="bg-light">
                                <td colspan="7" class="text-end"><strong>Category Total:</strong></td>
                                <td><strong>{{ number_format($categoryTotalStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalValue, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalPhyStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalPhyValue, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalSettStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalSettValue, 2) }}</strong></td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="14">{{ __('No data available') }}</td>
                            </tr>
                        @endforelse
                        
                        {{-- Grand Total --}}
                        <tfoot>
                            <tr class="bg-dark text-white">
                                <th colspan="7" class="text-end"><strong>Grand Total:</strong></th>
                                <th><strong>{{ number_format($grandTotalStock, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalValue, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalPhyStock, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalPhyValue, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalSettStock, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalSettValue, 2) }}</strong></th>
                                <th></th>
                            </tr>
                        </tfoot>
                </table>
        </div>

    </div>
</body>

</html>
