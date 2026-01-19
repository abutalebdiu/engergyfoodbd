@extends('admin.layouts.app', ['title' => __('Balance Sheets')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Balance Sheets')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" name="date" class="form-control"
                                @if (isset($date)) value="{{ $date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
                    </div>
                </div>
            </form>

            @if ($searching == 'Yes')
                <div class="row mt-4">
                    <div class="col-12">
                        <p class=" mt-5">@lang('Date'): @if (isset($date))
                                {{ en2bn(Date('d-m-Y', strtotime($date))) }}
                            @endif
                        </p>
                       
                    </div>
                </div>
            @endif




        </div>

    </div>
@endsection
