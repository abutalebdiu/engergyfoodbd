@extends('report.print.layouts.app')

@section('title')
স্টক সমন্বয় প্রতিবেদন
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>@lang('নাম')</th>
                <th>@lang('বিভাগ')</th>
                <th>@lang('ব্র্যান্ড')</th>
                <th>@lang('পরিমাণ সামঞ্জস্য')</th>
            </tr>
        </thead>
        @include('report.print.inc.stockadjustment')
    </table>
</div>
@endsection


