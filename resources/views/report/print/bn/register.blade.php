@extends('report.print.layouts.app')

@section('title')
রেজিস্টার রিপোর্ট
@endsection

@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>খোলা</th>
                <th>বন্ধ</th>
                <th>যোগাযোগ</th>
                <th>মোট ক্রয়</th>
                <th>মোট ক্রয় ফেরত</th>
                <th>মোট বিক্রয়</th>
                <th>মোট বিক্রয় ফেরত</th>
                <th>খোলার বকেয়া ব্যালেন্স</th>
                <th>বকেয়া</th>
            </tr>
        </thead>

        @include('report.print.inc.register')

    </table>
</div>
@endsection
