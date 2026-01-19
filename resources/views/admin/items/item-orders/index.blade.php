@extends('admin.layouts.app', ['title' => 'Item Order list'])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                Item Order list
            </h6>

            <div>
                <a href="{{ route('admin.items.itemOrder.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="fa fa-plus"></i> New Item Order</a>
            </div>
        </div>
        <div class="card-body">
            <form action="" id="searchForm">
                <div class="mb-3 row">
                    <div class="col-12 col-md-4">
                        <select name="supplier_id" id="supplier_id" class="form-select select2">
                            <option value="">@lang('Search Supplier')</option>
                            @foreach ($suppliers as $supplier)
                                <option
                                    @if (isset($supplier_id)) {{ $supplier_id == $supplier->id ? 'selected' : '' }} @endif
                                    value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                        <button type="submit" name="search" id="searchBtn" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>

                    </div>
                </div>
            </form>
            <div class="table-responsive" id="table">

            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection


@push('script')
<script>
    $(document).ready(function(){
        getLists("{{ route('admin.items.itemOrder.index') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();

            getLists("{{ route('admin.items.itemOrder.index') }}", "table", "searchForm");
        });
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        getLists("{{ route('admin.items.itemOrder.index') }}", "table", "searchForm", page);
    });

</script>
@endpush
