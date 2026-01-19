@extends('report.print.layouts.app')

@section('title')
তারিখ অনুযায়ী  ক্রয় পণ্যের স্টক তালিকা
@endsection

@section('content')
@php
    $chunks = array_chunk($dates, 4); // 4 dates per table
    $sl_global = 1; // global SL counter
@endphp

@foreach($chunks as $chunk)
    <table class="table table-bordered table-striped mb-4">
        <thead>
            <tr>
                <th rowspan="2">সি নং</th>
                <th rowspan="2">আইটেম </th>
                @foreach($chunk as $date)
                    <th colspan="4" class="text-center">{{ en2bn($date) }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($chunk as $date)
                    <th>পূর্বের</th>
                    <th>ক্রয়</th>
                    <th>ব্যবহিত</th>
                    <th>বর্তমান</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($categoryItems as $categoryItem)
                <tr class="dept-row">
                    <td colspan="{{ count($chunk) * 4 + 2 }}" style="text-align: left; font-weight: bold">
                        {{ $categoryItem['category_name'] ?? 'Unknown' }}
                    </td>
                </tr>

                @foreach($categoryItem['items'] as $item)
                    <tr>
                        <td>{{ en2bn($sl_global++) }}</td> {{-- SL number --}}
                        <td style="white-space: nowrap;">{{ $item['name'] }}</td>
                        @foreach($chunk as $date)
                            @if(isset($report_data[$date][$item->id]))
                                <td>{{ en2bn($report_data[$date][$item->id]['previous_stock'] ?? 0) }}</td>
                                <td>{{ en2bn($report_data[$date][$item->id]['purchase'] ?? 0) }}</td>
                                <td>{{ en2bn($report_data[$date][$item->id]['used'] ?? 0) }}</td>
                                <td>{{ en2bn($report_data[$date][$item->id]['current_stock'] ?? 0) }}</td>
                            @else
                                <td colspan="4" class="text-center">{{ __('No data') }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals-row">
                <th colspan="2">টোটাল</th>
                @foreach($chunk as $date)
                    <th>{{ en2bn($totals[$date]['previous_stock'] ?? 0) }}</th>
                    <th>{{ en2bn($totals[$date]['purchase'] ?? 0) }}</th>
                    <th>{{ en2bn($totals[$date]['used'] ?? 0) }}</th>
                    <th>{{ en2bn($totals[$date]['current_stock'] ?? 0) }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
@endforeach
@endsection
