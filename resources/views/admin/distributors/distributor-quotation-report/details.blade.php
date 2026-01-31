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
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">SL</th>
                            <th>Product Name</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-end">DC Price</th>
                            <th class="text-end">DC Amount</th>
                            <th class="text-end">Product Commission</th>
                            <th class="text-end">DC Product Commission</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($details as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td class="fw-semibold">
                                    {{ $row->product_name }}
                                </td>

                                <td class="text-end fw-bold">
                                    {{ number_format($row->total_qty) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->price ?? 0, 2) }}
                                </td>

                                <td class="text-end fw-bold">
                                    {{ number_format($row->total_amount, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->dc_price ?? 0, 2) }}
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
                                <td colspan="9" class="text-center text-muted py-4">
                                    No data found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="table-light fw-bold">
                        <tr>
                            <th colspan="2" class="text-end">Grand Total</th>

                            <th class="text-end">
                                {{ number_format($details->sum('total_qty')) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grandTotal->price ?? 0, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grandTotal->total_amount ?? 0, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grandTotal->dc_price ?? 0, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grandTotal->dc_amount ?? 0, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grandTotal->product_commission ?? 0, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grandTotal->dc_product_commission ?? 0, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection