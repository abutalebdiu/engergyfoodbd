@extends('report.print.layouts.app')

@section('title')
Stock Adjustment Reports
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>@lang('Name')</th>
                <th>@lang('Category')</th>
                <th>@lang('Brand')</th>
                <th>@lang('Quantity Adjust')</th>
            </tr>
        </thead>
        @include('report.print.inc.stockadjustment')
    </table>
</div>
@endsection


