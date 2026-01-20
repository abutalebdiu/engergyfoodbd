@extends('admin.layouts.app', ['title' => 'Distributor Order Report'])

@section('panel')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Distributor Order Report</h5>
    </div>

    <div class="card-body">

        {{-- Search --}}
        <form action="{{ route('admin.distributor-orders.index') }}" method="GET">
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}">
                </div>
                <div class="col-md-3">
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}">
                </div>
                <div class="col-md-3">
                    <select id="distribution_id" class="form-control" name="distribution_id">
                        <option value="">-- All Distributor --</option>
                        @foreach($distributors as $dist)
                            <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" id="searchBtn">Search</button>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100" id="searchBtn" name="pdf">PDF</button>
                </div>
            </div>
        </form>

        {{-- AJAX Table --}}
        <div id="orderReportTable">
            @include('admin.distributors.distributor-order-report.table')
        </div>

    </div>
</div>
@endsection

@push('script')
<script>
function loadOrders(page = 1) {
    $.ajax({
        url: "{{ route('admin.distributor-orders.index') }}",
        data: {
            page: page,
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            distribution_id: $('#distribution_id').val()
        },
        success: function (data) {
            $('#orderReportTable').html(data);
        }
    });
}

$('#searchBtn').on('click', function (e) {
    e.preventDefault();
    loadOrders();
});

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    loadOrders(page);
});
</script>
@endpush
