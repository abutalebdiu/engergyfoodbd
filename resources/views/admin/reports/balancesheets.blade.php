@extends('admin.layouts.app', ['title' => __('Balance Sheets')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Balance Sheets')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get" id="searchForm">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                @if (isset($start_date)) value="{{ $start_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                @if (isset($end_date)) value="{{ $end_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary mt-4" id="searchBtn"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Balanche Sheets</h5>
        </div>
        <div class="card-body">
           <div id="table"></div>
        </div>
    </div>
@endsection


@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.reports.balancesheets') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();
            getLists("{{ route('admin.reports.balancesheets') }}", "table", "searchForm");
        });
    });
</script>
@endpush