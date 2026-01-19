@extends('report.print.layouts.app')

@section('title')
    Register Report
@endsection
@section('content')
    <div class="mt-10">
        <table style="width: 100%" border="1">
            <thead>
                <tr>
                    <th>@lang('Sl No')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Current Stock Qty')</th>
                    <th>@lang('Price')</th>
                    <th>@lang('Current Total Value')</th>
                </tr>
            </thead>

            @include('report.print.inc.stock')
        </table>
    </div>
@endsection
