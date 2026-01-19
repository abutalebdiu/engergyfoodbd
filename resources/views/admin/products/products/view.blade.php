@extends('admin.layouts.app', ['title' => __('Products List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Products List')
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add New Product')</a>
            </h5>
        </div>
        <div class="card-body">
            <form action="" id="searchForm">
                <div class="row row-cols-3 row-cols-md-3 mb-3 g-2">
                    <div class="col">
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" id="name"
                                @if (isset($name)) value="{{ $name }}" @endif
                                placeholder="Product Name">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <select name="department_id" class="select2 form-control department_id">
                                <option value="">Select</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department->id ==  request()->department_id ? "selected" : ""  }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" name="search" id="search" class="btn btn-primary"><i class="fa fa-search"></i>
                                @lang('Search')</button>
                            <button type="submit" name="pdf" class="btn btn-info"> PDF</button>
                            <button type="submit" name="pdf2" class="btn btn-info"> PDF 2</button>
                            <button type="submit" name="pdf3" class="btn btn-info">Stock PDF </button>
                            <button type="submit" name="recipe" class="btn btn-info"> @lang('Recipe')</button>
                            <button type="submit" name="excel" class="btn btn-success"> Excel</button>
                        </div>
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
        $(document).ready(function() {
            $("#code").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#productsTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#name").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#productsTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>


    <script>
        $(document).ready(function(){

            $('#search').on('click', function(e){
                e.preventDefault();

                getLists("{{ route('admin.product.index') }}", "table", "searchForm");
            });


            getLists("{{ route('admin.product.index') }}", "table");
        });
    </script>
@endpush
