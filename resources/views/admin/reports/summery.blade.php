@extends('admin.layouts.app', ['title' => __('Summery')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Summery Report')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">

                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($datas['start_date'])) value="{{ $datas['start_date'] }}" @endif
                            class="form-control">
                    </div>

                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($datas['end_date'])) value="{{ $datas['end_date'] }}" @endif class="form-control">
                    </div>

                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary "><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary "><i class="bi bi-download"></i>
                            @lang('PDF')</button>

                    </div>

                </div>
            </form>


            <div class="row mt-4">
                <div class="col-12">
                    <p class=" mt-5">@lang('Date'): @if (isset($datas['start_date']))
                            {{ en2bn(Date('d-m-Y', strtotime($datas['start_date']))) }} -
                            {{ en2bn(Date('d-m-Y', strtotime($datas['end_date']))) }}
                        @endif
                    </p>

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    @include('admin.reports.includes.summery_table')
                </table>
            </div>



        </div>

    </div>
@endsection
