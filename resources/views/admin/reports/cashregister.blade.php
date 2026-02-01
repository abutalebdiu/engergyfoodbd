@extends('admin.layouts.app', ['title' => __('Cash Register')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Cash Register')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get" id="searchForm">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">From Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">To Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">
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

            <div class="table-responsive mt-3" id="table">
               
            </div>
        </div>

    </div>
@endsection



@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.reports.cashregister') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();
            getLists("{{ route('admin.reports.cashregister') }}", "table", "searchForm");
        });
    });
</script>
@endpush