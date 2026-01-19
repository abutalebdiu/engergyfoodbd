@php
    $chunks = array_chunk($dates, 4); // 4 dates per table
@endphp

@foreach($chunks as $chunk)
    <table class="table table-bordered table-striped mb-4">
        <thead>
            <tr>
                <th rowspan="2">@lang('Item')</th>
                @foreach($chunk as $date)
                    <th colspan="4" class="text-center">{{ $date }}</th>
                @endforeach
            </tr>
            <tr>

                @foreach($chunk as $date)
                    <th>Prev Stock</th>
                    <th>Purchase</th>
                    <th>Used</th>
                    <th>Current Stock</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($categoryItems as $categoryItem)
                <tr>
                    <td colspan="{{ count($chunk) * 4 + 1 }}" style="text-align: left; font-weight: bold">
                        {{ $categoryItem['category_name'] ?? 'Unknown' }}
                    </td>
                </tr>

                @foreach($categoryItem['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        @foreach($chunk as $date)
                            @if(isset($report_data[$date][$item->id]))
                                <td>{{ $report_data[$date][$item->id]['previous_stock'] ?? 0 }}</td>
                                <td>{{ $report_data[$date][$item->id]['purchase'] ?? 0 }}</td>
                                <td>{{ $report_data[$date][$item->id]['used'] ?? 0 }}</td>
                                <td>{{ $report_data[$date][$item->id]['current_stock'] ?? 0 }}</td>
                            @else
                                <td colspan="4" class="text-center">{{ __('No data') }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>@lang('Totals')</th>
                @foreach($chunk as $date)
                    <th>{{ $totals[$date]['previous_stock'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['purchase'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['used'] ?? 0 }}</th>
                    <th>{{ $totals[$date]['current_stock'] ?? 0 }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
@endforeach
