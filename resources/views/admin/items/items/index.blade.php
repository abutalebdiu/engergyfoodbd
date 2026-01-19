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
                <form action="" method="get">
                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="@lang('Search')" id="search"
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
                                <button class="btn btn-primary" name="search">@lang('Search')</button>
                                <button class="btn btn-primary" name="pdf">@lang('PDF')</button>
                                <button class="btn btn-info" name="excel">@lang('Excel')</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive" id="table">

                </div>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#ItemTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });


            getLists("{{ route('admin.items.item.index') }}", "table");
        });
    </script>
@endpush
