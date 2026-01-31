<table class="table table-bordered table-striped align-middle">
    <thead class="table-light">
        <tr>
            <th width="60">#</th>
            <th width="60">SL</th>
            <th>Date</th>
            <th>Distributor</th>
            <th class="text-end">Total Qty</th>
            <th class="text-end">Amount</th>
            <th class="text-end">DC Amount</th>
            <th class="text-end">Product Commission</th>
            <th class="text-end">DC Product Commission</th>
        </tr>
    </thead>

    <tbody>
        @forelse($distributor_quotations as $row)
            <tr>
                <td>
                    <a href="{{ route('admin.distributor-quotations.show', [
                        'date' => $row->date,
                        'distribution_id' => $row->distribution_id
                    ]) }}"
                       class="btn btn-sm btn-info">
                        <i class="las la-eye"></i>
                    </a>
                </td>

                <td>{{ $loop->iteration + ($distributor_quotations->firstItem() - 1) }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                </td>

                <td>
                    {{ $row->distribution->name ?? '-' }}
                </td>

                <td class="text-end fw-bold">
                    {{ number_format($row->total_qty) }}
                </td>

                <td class="text-end fw-bold">
                    {{ number_format($row->total_amount, 2) }}
                </td>

                <td class="text-end text-primary fw-semibold">
                    {{ number_format($row->dc_amount ?? 0, 2) }}
                </td>

                <td class="text-end text-success fw-semibold">
                    {{ number_format($row->product_commission ?? 0, 2) }}
                </td>
                <td class="text-end text-success fw-semibold">
                    {{ number_format($row->dc_product_commission ?? 0, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    No data found
                </td>
            </tr>
        @endforelse
    </tbody>

    {{-- Optional: Footer Totals --}}
    @if($distributor_quotations->count())
        <tfoot class="table-light fw-bold">
            <tr>
                <td colspan="4" class="text-end">Total</td>
                <td class="text-end">
                    {{ number_format($distributor_quotations->sum('total_qty')) }}
                </td>
                <td class="text-end">
                    {{ number_format($distributor_quotations->sum('total_amount'), 2) }}
                </td>
                <td class="text-end">
                    {{ number_format($distributor_quotations->sum('dc_amount'), 2) }}
                </td>
                <td class="text-end">
                    {{ number_format($distributor_quotations->sum('product_commission'), 2) }}
                </td>
                <td class="text-end">
                    {{ number_format($distributor_quotations->sum('dc_product_commission'), 2) }}
                </td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $distributor_quotations->links() }}
</div>