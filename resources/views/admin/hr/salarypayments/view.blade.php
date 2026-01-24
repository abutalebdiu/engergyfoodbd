@extends('admin.layouts.app', ['title' => 'Salary Payment History List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Salary Payment History List
                <a href="{{ route('admin.salarypaymenthistory.create') }}" class="btn btn-outline-primary btn-sm float-end">
                    <i class="bi bi-plus"></i>Pay Salary</a>

                <a href="{{ route('admin.salarypayment.single.employee.salary') }}"
                    class="btn btn-outline-success btn-sm float-end me-2">
                    <i class="bi bi-plus"></i>Single Pay Salary</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="GET" id="searchForm">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <select name="employee_id" id="employee_id" class="form-select select2">
                            <option value="">@lang('Search Employee')</option>
                            @foreach ($employees as $employee)
                                <option {{ request()->employee_id == $employee->id ? 'selected' : '' }}
                                    value="{{ $employee->id }}"> {{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="button" name="search" class="btn btn-primary btn-sm" id="searchBtn"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
                    </div>
                </div>
            </form>

            <div id="table"></div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection


@push('script')
<script>
    $(document).ready(function(){
        getLists("{{ route('admin.salarypaymenthistory.index') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();

            getLists("{{ route('admin.salarypaymenthistory.index') }}", "table", "searchForm");
        });
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        getLists("{{ route('admin.salarypaymenthistory.index') }}", "table", "searchForm", page);
    });

</script>
@endpush