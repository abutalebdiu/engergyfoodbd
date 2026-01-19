@extends('admin.layouts.app', ['title' => 'Item List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <h6 class="m-0">@lang('Item List')
                    <a href="{{ route('admin.items.item.create') }}" class="btn btn-primary btn-sm float-end"> <i
                            class="fa fa-plus"></i> @lang('Add New')</a>
                </h6>
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form action="" method="get" id="searchForm">
                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <input type="text" name="search" placeholder="@lang('Search')" id="search"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <select class="form-control select2 item_category_id" name="item_category_id" id="item_category_id">
                                    <option value="">@lang('Select Category')</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request()->item_category_id == $category->id ? "selected" : "" }} >{{ $category->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <button type="submit" id="searchBtn" class="btn btn-primary" name="search">@lang('Search')</button>
                                <button type="submit" class="btn btn-primary" name="pdf">@lang('PDF')</button>
                                <button type="submit" class="btn btn-info" name="excel">@lang('Excel')</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive" id="table">

                </div>
                <div id="pagination"></div>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            loadItems();

            $("#item_category_id").on("change", function() {
                loadItems();
            });


            $("#searchBtn").on("click", function(e) {
                e.preventDefault();
                loadItems();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadItems(page);
            });
        });

        function loadItems(page = 1) {
            var categoryId = $("#item_category_id").val();
            var search = $("#search").val();
            var perPage = 15;

            $("#table").html(`
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">@lang('Loading...')</span>
                </div>
            `);

            $.ajax({
                url: "{{ route('admin.items.item.index') }}",
                type: 'GET',
                data: {
                    search: search,
                    item_category_id: categoryId,
                    page: page,
                    per_page: perPage
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $("#table").html(response.html);
                        $("#pagination").html(response.pagination);
                    }
                },
                error: function(xhr) {
                    $("#table").html(`
                    <div class="alert alert-danger">
                        @lang('Something went wrong')
                    </div>
                `); 
                },
                complete: function() {
                    
                }
            });
        }
    </script>
@endpush
