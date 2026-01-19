@extends('admin.layouts.app', ['title' => __('Order List')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Order List')
            </h6>
            <div>
                <a href="{{ route('admin.order.pos.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-plus"></i> @lang('New Order')</a>
            </div>
        </div>
        <div class="card-body">
            <form action="" id="searchForm">
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
                    <div class="pb-3 col-md-2">
                        <div class="form-group">
                            <select name="marketer_id" id="marketer_id" class="form-select select2 marketer_id">
                                <option value="">@lang('Select Marketer')</option>
                                @foreach ($marketers as $marketer)
                                    <option
                                        @if (isset($marketer_id)) {{ $marketer_id == $marketer->id ? 'selected' : '' }} @endif
                                        value="{{ $marketer->id }}">{{ $marketer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @else value="{{ date('Y-m-01') }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @else value="{{ date('Y-m-t') }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" id="searchBtn" class="btn btn-primary btn-sm mb-2"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-secondary btn-sm mb-2"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-dark btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                             <button type="submit" name="invoice" class="btn btn-success btn-sm"><i class="bi bi-download"></i>
                            @lang('All Invoice')</button>
                       <button type="submit" name="challan" class="btn btn-info btn-sm"><i class="bi bi-download"></i>
                            @lang('All Challan')</button>
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
        getLists("{{ route('admin.order.index') }}", "table");

        $("#searchBtn").click(function(e){
            e.preventDefault();

            getLists("{{ route('admin.order.index') }}", "table", "searchForm");
        });
    });
</script>
@endpush
