@extends('report.print.layouts.app')

@section('title')
গ্রাহক সরবরাহকারী প্রতিবেদন
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>যোগাযোগ</th>
                <th>মোট ক্রয়</th>
                <th>মোট ক্রয় রিটার্ন</th>
                <th>মোট বিক্রয়</th>
                <th>মোট বিক্রয় রিটার্ন</th>
                <th>প্রারম্ভিক বকেয়া ব্যালেন্স</th>
                <th>বকেয়া</th>
            </tr>
        </thead>

        @include('report.print.inc.customersupplier')
    </table>
</div>
@endsection


