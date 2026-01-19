@extends('admin.layouts.app', ['title' => __('Expense List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Expense List')
                <a href="{{ route('admin.expense.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add New Expense')</a>
            </h5>
        </div>
        <div class="card-body">
            <form action="" id="searchForm">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <select class="form-select select2" name="category_id">
                            <option value="">Select Category</option>
                            @foreach ($expensecategories as $category)
                                <option @if(isset($category_id))  {{ $category_id == $category->id ? 'selected' : '' }} @endif
                                    value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search"  id="searchBtn" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                    </div>
                </div>
            </form>
            
            
            <div id="table">

            </div>
        </div>
    </div>

     <x-destroy-confirmation-modal />
@endsection

@include('components.select2')


@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.expense.index') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();

            getLists("{{ route('admin.expense.index') }}", "table", "searchForm");
        });
    });
</script>
@endpush
