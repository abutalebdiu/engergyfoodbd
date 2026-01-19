@extends('report.print.layouts.app')

@section('title')
Product Purchase
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>@lang('Name')</th>
                <th>@lang('Category')</th>
                <th>@lang('Brand')</th>
                <th>@lang('Sku/Code')</th>
                <th>@lang('Supplier')</th>
                <th>@lang('Reference')</th>
                <th>@lang('Date')</th>
                <th>@lang('Quantity Adjusted')</th>
                <th>@lang('Quantity Price')</th>
                <th>@lang('Subtotal')</th>
            </tr>
        </thead>

        @include('report.print.inc.productpurchase')
    </table>
</div>
@endsection


