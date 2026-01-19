@extends('report.print.layouts.app')

@section('title')
পণ্যের বিক্রয় রিপোর্ট
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>@lang('নাম')</th>
                <th>@lang('এসকিউ/কোড')</th>
                <th>@lang('গ্রাহক')</th>
                <th>@lang('যোগাযোগ')</th>
                <th>@lang('রেফারেন্স')</th>
                <th>@lang('তারিখ')</th>
                <th>@lang('পরিমাণ')</th>
                <th>@lang('পরিমাণ মূল্য')</th>
                <th>@lang('ছাড়')</th>
                <th>@lang('কর/ভ্যাট')</th>
                <th>@lang('এআইটি')</th>
                <th>@lang('মোট')</th>
                <th>@lang('পেমেন্ট পদ্ধতি')</th>
            </tr>
        </thead>
        @include('report.print.inc.productsale')
    </table>
</div>
@endsection


