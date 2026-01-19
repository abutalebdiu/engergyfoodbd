@extends('admin.layouts.app', ['title' => __('Products Demand List')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Products Demand List')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get" id="formID">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" id="customer_id" class="form-select select2">
                            <option value="">@lang('Search Customer')</option>
                            @foreach ($customerss as $customer)
                                <option
                                    @if (isset($customer_id)) {{ $customer_id == $customer->id ? 'selected' : '' }} @endif
                                    value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">                            
                            <input type="date" name="start_date" class="form-control"
                                @if (isset($start_date)) value="{{ $start_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">                           
                            <input type="date" name="end_date" class="form-control"
                                @if (isset($end_date)) value="{{ $end_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" id="submitBTN" class="btn btn-primary  "><i
                                class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                         
                    </div>
                </div>
            </form>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="table-responsive" id="table">
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@include('components.select2')
@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.order.date.customerorder') }}", "table");

        $("#submitBTN").on("click", function(e) {
            e.preventDefault();

            getLists("{{ route('admin.order.date.customerorder') }}", "table", "formID");
        });
    });
</script>
@endpush