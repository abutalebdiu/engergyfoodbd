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
            <th class="text-end">মূল্য</th>
            <th class="text-end">মোট মূল্য</th>
            <th class="text-end">ডিসি মূল্য</th>
            <th class="text-end">ডিসি এমাউন্ট</th>
            <th class="text-end">কমিশন</th>
            <th class="text-end">ডিসি কমিশন</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($details as $key => $row)
            <tr>
                <td>{{ en2bn($key + 1) }}</td>
                <td>{{ $row->product_name }}</td>

                <td class="text-end">
                    {{ en2bn(number_format($row->total_qty)) }}
                </td>

                <td class="text-end">
                    {{ en2bn(number_format($row->price ?? 0, 2)) }}
                </td>

                <td class="text-end">
                    {{ en2bn(number_format($row->total_amount ?? 0, 2)) }}
                </td>

                <td class="text-end">
                    {{ en2bn(number_format($row->dc_price ?? 0, 2)) }}
                </td>

                <td class="text-end">
                    {{ en2bn(number_format($row->dc_amount ?? 0, 2)) }}
                </td>

                <td class="text-end">
                    {{ en2bn(number_format($row->product_commission ?? 0, 2)) }}
                </td>

                <td class="text-end">
                    {{ en2bn(number_format($row->dc_product_commission ?? 0, 2)) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">কোন তথ্য পাওয়া যায়নি</td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <th colspan="2" class="text-end">সর্বমোট</th>

            <th class="text-end">
                {{ en2bn(number_format($details->sum('total_qty'))) }}
            </th>

            <th class="text-end">
                {{ en2bn(number_format($grandTotal->price ?? 0, 2)) }}
            </th>

            <th class="text-end">
                {{ en2bn(number_format($grandTotal->total_amount ?? 0, 2)) }}
            </th>

            <th class="text-end">
                {{ en2bn(number_format($grandTotal->dc_price ?? 0, 2)) }}
            </th>

            <th class="text-end">
                {{ en2bn(number_format($grandTotal->dc_amount ?? 0, 2)) }}
            </th>

            <th class="text-end">
                {{ en2bn(number_format($grandTotal->product_commission ?? 0, 2)) }}
            </th>

            <th class="text-end">
                {{ en2bn(number_format($grandTotal->dc_product_commission ?? 0, 2)) }}
            </th>
        </tr>
    </tfoot>
</table>
@endsection