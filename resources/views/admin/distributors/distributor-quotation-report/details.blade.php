@extends('admin.layouts.app', ['title' => 'Order Details'])

@section('panel')
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">
				Quotation Details - {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
			</h5>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.distributor-quotations.show', [
                            'date' => $date,
                            'distribution_id' => $distributionId,
                            'type' => 'pdf'
                        ]) }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-download"></i> PDF
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">
                    ← Back
                </a>
            </div>
		</div>

		<div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>SL</th>
						<th>Product Name</th>
						<th>Total Qty</th>
						<th>Total Amount</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($details as $key => $row)
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>{{ $row->product_name }}</td>
							<td class="text-end">{{ $row->total_qty }}</td>
							<td class="text-end">{{ number_format($row->total_amount, 2) }}</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3" class="text-end">Grand Total</th>
						<th class="text-end">{{ number_format($grandTotal, 2) }}</th>
					</tr>
				</tfoot>
			</table>

		</div>
	</div>
@endsection
