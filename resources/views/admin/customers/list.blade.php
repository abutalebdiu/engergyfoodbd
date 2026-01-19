@extends('admin.layouts.app', ['title' => __('All Customers')])
@section('panel')
    <div class="card">

        <div class="py-3 card-header">
            <h6 class="mb-0">@lang('All Customers') <a href="{{ route('admin.customers.create') }}"
                    class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-plus"></i> @lang('Add New Customer')</a></h6>
        </div>

        <div class="card-body">
            <form id="search-form" action="" method="get">
                <div class="mb-3 row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <input type="text" name="uid" id="uid" class="form-control"
                                value="{{ request()->uid }}" placeholder="@lang('Customer ID')">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="reference_id" id="reference_id" class="form-select">
                                <option value="">@lang('Select Marketer')</option>
                                @foreach ($marketers as $marketer)
                                    <option {{ request()->reference_id == $marketer->id ? 'selected' : '' }}
                                        value="{{ $marketer->id }}">{{ $marketer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="distribution_id" id="distribution_id" class="form-select">
                                <option value="">@lang('Select Distributor')</option>
                                @foreach ($distributions as $distributor)
                                    <option {{ request()->distribution_id == $distributor->id ? 'selected' : '' }}
                                        value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <select name="paginate" id="paginate" class="form-select">
                                <option value="">@lang('--Select--')</option>
                                <option {{ request()->paginate == '50' ? 'selected' : '' }} value="50">50</option>
                                <option {{ request()->paginate == '100' ? 'selected' : '' }} value="100">100</option>
                                <option {{ request()->paginate == '150' ? 'selected' : '' }} value="150">150</option>
                                <option {{ request()->paginate == '200' ? 'selected' : '' }} value="200">200</option>
                                <option {{ request()->paginate == '300' ? 'selected' : '' }} value="300">300</option>
                                <option {{ request()->paginate == '500' ? 'selected' : '' }} value="500">500</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" id="search" class="btn btn-primary">@lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary">@lang('PDF')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <input type="text" id="customer_search" name="customer_search" class="form-control mb-3"
                placeholder="কাস্টমার নাম দিয়ে খুজুন">
            <div class="table-responsive" id="table">

            </div>

        </div>
    </div>
@endsection

@include('components.select2')

@push('script')
    <script>
        $(document).ready(function() {

            $("#customer_search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#customerlists tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            getLists("{{ route('admin.customers.all') }}", "table");


        });

        $(document).on('click', '#table .pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            getLists("{{ route('admin.customers.all') }}", "table", "search-form", page);
        });


        $(document).on('click', '#search', function(e) {
            e.preventDefault();

            getLists("{{ route('admin.customers.all') }}", "table", "search-form");
        });
    </script>
@endpush
