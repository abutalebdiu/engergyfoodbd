@extends('admin.layouts.app', ['title' => __('Trial Balance')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Trail Balance')
            </h6>
        </div>
        <div class="card-body">
            <form id="searchForm" action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">From Date</label>
                            <input type="date" name="start_date" class="form-control"
                                @if (isset($start_date)) value="{{ $start_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">To Date</label>
                            <input type="date" name="end_date" class="form-control"
                                @if (isset($end_date)) value="{{ $end_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" id="searchBtn" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                    </div>
                </div>
            </form>

            <div id="table"></div>
        </div>

    </div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.reports.trialbalance') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();

            getLists("{{ route('admin.reports.trialbalance') }}", "table", "searchForm");
        });
    });
</script>
@endpush
