<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Distributor</th>
            <th class="text-end">Total Qty</th>
            <th class="text-end">Total Amount</th>
            <th class="text-center">Action</th>
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
                <td class="text-center">
                    <a href="{{ route('admin.distributor-orders.show', [
                        'date' => $row->date,
                        'distribution_id' => $row->distribution_id
                    ]) }}" class="btn btn-sm btn-primary">
                        View Details
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found</td>
            </tr>
        @endforelse

        @if($distributor_orders->count())
            <tr class="table-secondary">
                <td colspan="2" class="fw-bold text-end">Grand Total</td>
                <td class="fw-bold text-end">{{ number_format($totalQty) }}</td>
                <td class="fw-bold text-end">{{ number_format($totalAmount, 2) }}</td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="d-flex justify-content-end">
    {{ $distributor_orders->links() }}
</div>
