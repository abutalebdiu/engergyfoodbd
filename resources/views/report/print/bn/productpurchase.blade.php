@extends('report.print.layouts.app')

@section('title')
পণ্যের ক্রয়
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>@lang('নাম')</th>
                <th>@lang('বিভাগ')</th>
                <th>@lang('ব্র্যান্ড')</th>
                <th>@lang('এসকিউ/কোড')</th>
                <th>@lang('সরবরাহকারী')</th>
                <th>@lang('রেফারেন্স')</th>
                <th>@lang('তারিখ')</th>
                <th>@lang('পরিমাণ সমন্বয়')</th>
                <th>@lang('পরিমাণ মূল্য')</th>
                <th>@lang('মোট')</th>
            </tr>
        </thead>
        @include('report.print.inc.productpurchase')
    </table>
</div>
@endsection


