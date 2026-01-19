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
                        <div class="form-group">
                            <label for="">@lang('Date')</label>
                            <input type="date" name="start_date" class="form-control"
                                @if (isset($start_date)) value="{{ $start_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('Date')</label>
                            <input type="date" name="end_date" class="form-control"
                                @if (isset($end_date)) value="{{ $end_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">Print Format</label>
                            <select name="format" id="format" class="form-control">
                                <option @if (isset($format)) {{ $format == 'A4' ? 'selected' : '' }} @endif
                                    value="A4">A4</option>
                                <option @if (isset($format)) {{ $format == 'A1' ? 'selected' : '' }} @endif
                                    value="A1">A1</option>
                                <option @if (isset($format)) {{ $format == 'A2' ? 'selected' : '' }} @endif
                                    value="A2">A2</option>
                                <option @if (isset($format)) {{ $format == 'A3' ? 'selected' : '' }} @endif
                                    value="A3">A3</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">Page Orientation</label>
                            <select name="orientation" id="orientation" class="form-control">
                                <option @if (isset($orientation)) {{ $orientation == 'P' ? 'selected' : '' }} @endif
                                    value="P">Portrait</option>
                                <option @if (isset($orientation)) {{ $orientation == 'L' ? 'selected' : '' }} @endif
                                    value="L">Landscape</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">Report Type</label>
                            <select name="type" id="type" class="form-control">
                                <option @if (isset($type)) {{ $type == 'WC' ? 'selected' : '' }} @endif
                                    value="WC">@lang('With Customer')</option>
                                <option @if (isset($type)) {{ $type == 'WOC' ? 'selected' : '' }} @endif
                                    value="WOC">@lang('Without Customer')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" id="submitBTN" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
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

@push('script')
<script>
    $(document).ready(function() {
        getLists("{{ route('admin.order.product.demand') }}", "table");

        $("#submitBTN").on("click", function(e) {
            e.preventDefault();

            getLists("{{ route('admin.order.product.demand') }}", "table", "formID");
        });
    });
</script>
@endpush
