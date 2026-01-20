<table class="table table-bordered table-striped">

    <thead>
        <tr>
            <th>#</th>
            <th>SL</th>
            <th>Date</th>
            <th>Distributor</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Amount</th>
        </tr>
    </thead>

    <tbody>

        @forelse($distributor_quotations as $row)

            <tr>
                <td>
                    <a href="{{ route('admin.distributor-quotations.show', ['date' => $row->date, 'distribution_id' => $row->distribution_id]) }}" class="btn btn-sm btn-info">
                        <i class="las la-eye"></i>
                    </a>
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                <td>{{ $row->distribution->name ?? '-' }}</td>
                <td class="text-end fw-bold">{{ $row->total_qty }}</td>
                <td class="text-end fw-bold">{{ $row->total_amount }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="6" class="text-center text-muted">No data found</td>
            </tr>

        @endforelse

    </tbody>
</table>

<div class="d-flex justify-content-end">
    {{ $distributor_quotations->links() }}
</div>
