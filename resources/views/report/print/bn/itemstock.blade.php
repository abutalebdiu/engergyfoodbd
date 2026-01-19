@extends('report.print.layouts.app')

@section('title')
   Item Stock Reports
@endsection
@section('content')
    <div class="mt-10">
        <table class="table table-hover table-striped">
            <tr>
                <th>@lang('SL No')</th>
                <th>@lang('নাম')</th>
                <th>@lang('বর্তমান স্টক')</th>
                <th>@lang('মূল্য')</th>
                <th>@lang('মোট মূল্য')</th>
            </tr>
            @include('report.print.inc.itemstock')
        </table>
    </div>
@endsection
