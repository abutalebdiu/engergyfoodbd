@extends('report.print.layouts.app')

@section('title')
Distributor Order Report
@endsection

@section('content')
<table class="table table-bordered table-striped">

    <thead>
        <tr>
            <th>SL</th>
            <th>Date</th>
            <th>Distributor</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Amount</th>
            <th class="text-end">DC Amount</th>
             <th class="text-end">Product Commission</th>
            <th class="text-end">DC Product Commission</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalQty = 0;
            $totalAmount = 0;
        @endphp

        @forelse($distributor_quotations as $row)
            @php
                $totalQty += $row->total_qty;
                $totalAmount += $row->total_amount;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                <td>{{ $row->distribution->name ?? '-' }}</td>
                <td class="text-end">{{ number_format($row->total_qty) }}</td>
                <td class="text-end">{{ number_format($row->total_amount, 2) }}</td>
                <td class="text-end">
                    {{ number_format($row->dc_amount ?? 0, 2) }}
                </td>

                <td class="text-end">
                    {{ number_format($row->product_commission ?? 0, 2) }}
                </td>
                <td class="text-end">
                    {{ number_format($row->dc_product_commission ?? 0, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found</td>
            </tr>
        @endforelse

        @if($distributor_quotations->count())
            <tr class="table-secondary">
                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                <td class="text-end fw-bold">{{ number_format($totalQty) }}</td>
                <td class="text-end fw-bold">{{ number_format($totalAmount, 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($distributor_quotations->sum('dc_amount'), 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($distributor_quotations->sum('product_commission'), 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($distributor_quotations->sum('dc_product_commission'), 2) }}</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection
