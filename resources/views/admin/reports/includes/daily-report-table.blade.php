@php
    $chunks = array_chunk($dates, 4);
@endphp
@foreach($chunks as $chunk)
    <table class="table table-bordered table-striped mb-4">
        <thead>
            <tr>
                <th rowspan="2">Product</th>
                @foreach($chunk as $date)
                    <th colspan="7" class="text-center">{{ $date }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($chunk as $date)
                    <th>Prev Stock</th>
                    <th>Production</th>
                    <th>Returns</th>
                    <th>Sales</th>
                    <th>C. Damage</th>
                    <th>SP. Damage</th>
                    <th>Current Stock</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($departmentProducts as $departmentProduct)
                <tr>
                    <td colspan="{{ count($chunk) * 7 + 1 }}" style="text-align: left; font-weight: bold">
                        {{ $departmentProduct['department_name'] ?? 'Unknown' }}
                    </td>
                </tr>
                @foreach($departmentProduct['products'] as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        @foreach($chunk as $date)
                            @if(isset($report_data[$date][$product->id]))
                                <td>{{ $report_data[$date][$product->id]['previous_stock'] }}</td>
                                <td>{{ $report_data[$date][$product->id]['production'] }}</td>
                                <td>{{ $report_data[$date][$product->id]['returns'] }}</td>
                                <td>{{ $report_data[$date][$product->id]['sales'] }}</td>
                                <td>{{ $report_data[$date][$product->id]['damaged'] }}</td>
                                <td>{{ $report_data[$date][$product->id]['product_damaged'] }}</td>
                                <td>{{ $report_data[$date][$product->id]['current_stock'] }}</td>
                            @else
                                <td colspan="7" class="text-center">No data</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Totals</th>
                @foreach($chunk as $date)
                    <th>{{ $totals[$date]['previous_stock'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['production'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['returns'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['sales'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['damaged'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['product_damaged'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['current_stock'] ?? 0 }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
@endforeach