@extends('report.print.layouts.app')

@section('title')
Product Sale
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>@lang('Name')</th>
                <th>@lang('Sku/Code')</th>
                <th>@lang('Customer')</th>
                <th>@lang('Contact')</th>
                <th>@lang('Reference')</th>
                <th>@lang('Date')</th>
                <th>@lang('Quantity')</th>
                <th>@lang('Quantity Price')</th>
                <th>@lang('Discount')</th>
                <th>@lang('Tax/Vat')</th>
                <th>@lang('Ait')</th>
                <th>@lang('Total')</th>
                <th>@lang('Payment Method')</th>
            </tr>
        </thead>

        @include('report.print.inc.productsale')
    </table>
</div>
@endsection


