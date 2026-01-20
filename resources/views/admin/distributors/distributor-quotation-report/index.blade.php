@extends('admin.layouts.app', ['title' => 'Distributor Quotation Report'])

@section('panel')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Distributor Quotation Report</h5>
    </div>

    <div class="card-body">

        {{-- Search Form --}}
        <form action="{{ route('admin.distributor-quotations.index') }}" method="GET">
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" id="start_date" class="form-control" name="start_date" value="{{ $start_date }}">
                </div>
                <div class="col-md-3">
                    <input type="date" id="end_date" class="form-control" name="end_date" value="{{ $end_date }}">
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
                    <button type="submit" class="btn btn-primary w-100" id="searchPDF" name="pdf">PDF</button>
                </div>
            </div>
        </form>

        <div id="reportTable">
            @include('admin.distributors.distributor-quotation-report.table')
        </div>

    </div>
</div>
@endsection

@push('script')
<script>
function loadData(page = 1) {
    $.ajax({
        url: "{{ route('admin.distributor-quotations.index') }}",
        data: {
            page: page,
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            distribution_id: $('#distribution_id').val()
        },
        success: function (data) {
            $('#reportTable').html(data);
        }
    });
}

$('#searchBtn').on('click', function (e) {
    e.preventDefault();
    loadData();
});

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    loadData(page);
});
</script>
@endpush
