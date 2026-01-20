@extends('report.print.layouts.app')

@section('title')
Distributor Order Report
@endsection

@section('content')
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Distributor</th>
            <th class="text-end">Total Qty</th>
            <th class="text-end">Total Amount</th>
        </tr>
    </thead>
    <tbody>

        @php
            $totalQty = 0;
            $totalAmount = 0;
        @endphp

        @forelse($distributor_orders as $row)
            @php
                $totalQty += $row->total_qty;
                $totalAmount += $row->total_amount;
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                <td>{{ $row->distribution->name ?? '-' }}</td>
                <td class="text-end">{{ number_format($row->total_qty) }}</td>
                <td class="text-end">{{ number_format($row->total_amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No data found</td>
            </tr>
        @endforelse

        @if($distributor_orders->count())
            <tr class="table-secondary">
                <td colspan="2" class="text-end fw-bold">Grand Total</td>
                <td class="text-end fw-bold">{{ number_format($totalQty) }}</td>
                <td class="text-end fw-bold">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection
