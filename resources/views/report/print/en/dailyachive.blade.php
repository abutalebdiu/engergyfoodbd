@extends('report.print.layouts.app')

@section('title')
    Daily Archive
@endsection
@section('content')
    <div class="mt-10">
        <table class="table table-hover table-striped">
            <tr>
                <th>@lang('নং')</th>
                <th>@lang('তারিখ')</th>
                <th>@lang('Quotation Qty')</th>
                <th>@lang('Quotation Amount')</th>
                <th>@lang('Order Qty')</th>
                <th>@lang('Order Amount')</th>
                <th>@lang('Paid Amount')</th>
                <th>@lang('Order Due')</th>
                <th>@lang('Customer Due Payment')</th>
            </tr>
            @include('report.print.inc.dailyarchive')
        </table>
    </div>
@endsection
