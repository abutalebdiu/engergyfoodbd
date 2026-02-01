@extends('admin.layouts.app', ['title' => __('Monthly Customer Summery')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Monthly Customer Summery')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get" id="searchForm">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" class="form-control select2">
                            <option value="">@lang('Select Customer')</option>
                            @foreach ($allcustomers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->uid }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="month" class="form-control">
                            <option value="">@lang('Select Month')</option>
                            @foreach ($months as $month)
                                <option value="{{ $month->id }}">
                                    {{ $month->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="year" class="form-control">
                            <option value="">@lang('Select Year')</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->name }}">
                                    {{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary" id="searchBtn"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary "><i class="bi bi-download"></i>
                            @lang('PDF')</button>

                    </div>

                </div>
            </form>

            <div class="table-responsive" id="table">
                
            </div> 
        </div>

    </div>
@endsection
@include('components.select2')


@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.reports.monthlycustomersummary') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();
            getLists("{{ route('admin.reports.monthlycustomersummary') }}", "table", "searchForm");
        });
    });
</script>
@endpush