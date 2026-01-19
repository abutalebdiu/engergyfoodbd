@extends('admin.layouts.app', ['title' => __('Productions List')])
@section('panel')
    <div class="mt-5">
        <h1 class="mb-4">Production Report</h1>

        <!-- Filter Section -->
        <form method="GET" action="" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}">
            </div>
            <div class="col-md-3">
                <label for="department_id" class="form-label">Department</label>
                <select id="department_id" name="department_id" class="form-select">
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ $department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Yeast</th>
                        <th>Production Quantity</th>
                        <th>Production Cost</th>
                        <th>Received Item Qty</th>
                        <th>Received Item Cost</th>
                        <th>Profit/Loss</th>
                        <th>Profit/Loss %</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['yeast'] }}</td>
                            <td>{{ $item['production_qty'] }}</td>
                            <td>{{ number_format($item['production_cost'], 2) }}</td>
                            <td>{{ $item['received_item_qty'] }}</td>
                            <td>{{ number_format($item['received_item_cost'], 2) }}</td>
                            <td class="{{ $item['profit_or_loss'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($item['profit_or_loss'], 2) }}
                            </td>
                            <td>{{ number_format($item['profit_or_loss_percentage'], 2) }}%</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="collapse"
                                    data-bs-target="#details-{{ $item['id'] }}">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="details-{{ $item['id'] }}">
                            <td colspan="10">
                                <strong>Items:</strong>
                                <table class="table table-sm table-bordered mt-2">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Received Qty</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item['items'] as $subItem)
                                            <tr>
                                                <td>{{ $subItem['id'] }}</td>
                                                <td>{{ $subItem['name'] }}</td>
                                                <td>{{ number_format($subItem['price'], 2) }}</td>
                                                <td>{{ $subItem['received_qty'] }}</td>
                                                <td>{{ number_format($subItem['total_cost'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
