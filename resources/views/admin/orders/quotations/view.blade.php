@extends('admin.layouts.app', ['title' => __('Quotation List')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Quotation List')
            </h6>

            <div>
                <a href="{{ route('admin.quotation.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-plus"></i> @lang('New Quotation')</a>
            </div>
        </div>
        <div class="card-body">
            <form action="" id="filterFormData">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" id="customer_id" class="form-select select2">
                            <option value="">@lang('Search Customer')</option>

                            @foreach ($customers as $customer)
                                <option
                                    @if (isset($customer_id)) {{ $customer_id == $customer->id ? 'selected' : '' }} @endif
                                    value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
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

                    <div class="col-12 col-md-5">
                        <button type="submit" name="search" id="filterForm" class="btn btn-primary"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                       <button type="submit" name="invoice" class="btn btn-primary"><i class="bi bi-download"></i>
                            @lang('All Invoice')</button>
                       <button type="submit" name="challan" class="btn btn-primary"><i class="bi bi-download"></i>
                            @lang('All Challan')</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive" id="quotationTable">

            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

@include('components.select2')


@push('script')
<script>
$(document).ready(function() {

    getList();

    // Load list with filter + pagination
    function getList(page = 1) {
        let formData = $('#filterFormData').serialize();
        $.ajax({
            url: "{{ route('admin.quotation.index') }}?page=" + page,
            type: "GET",
            data: formData,
            beforeSend: function() {
                $("#quotationTable").html('<div class="text-center p-3">Loading...</div>');
            },
            success: function(res) {
                if (res.status) {
                    $("#quotationTable").html(res.render_view);
                }
            },
            error: function() {
                toastr.error("Something went wrong!");
            }
        });
    }

    $('#filterForm').on('click', function(e) {
        e.preventDefault();
        getList(1);
    });

    $(document).on('click', '#quotationTable .pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        getList(page);
    });

});
</script>
@endpush


