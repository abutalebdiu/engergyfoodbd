@extends('report.print.layouts.app')

@section('title')
বিতরণ কোটেশন বিস্তারিত রিপোর্ট - {{ en2bn(\Carbon\Carbon::parse($date)->format('d-m-Y')) }}  
<br>
প্রদানকারীঃ {{ $distribtion_name }}
@endsection


@section('content')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ক্রমিক</th>
            <th>পণ্যের নাম</th>
            <th class="text-end">মোট পরিমাণ</th>
            <th class="text-end">মোট মূল্য</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $key => $row)
            <tr>
                <td>{{ en2bn($key + 1) }}</td>
                <td>{{ $row->product_name }}</td>
                <td class="text-end">{{ en2bn($row->total_qty) }}</td>
                <td class="text-end">{{ en2bn(number_format($row->total_amount, 2)) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">সর্বমোট</th>
            <th class="text-end">{{ en2bn(number_format($grandTotal, 2)) }}</th>
        </tr>
    </tfoot>
</table>
@endsection
