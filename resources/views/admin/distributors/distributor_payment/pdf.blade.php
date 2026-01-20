@extends('report.print.layouts.app')

@section('title')
Distributor Payment Report
@endsection

@section('content')
<table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ক্রমিক</th>
                    <th>ডিস্ট্রিবিউটার</th>
                    <th>তারিখ</th>
                    <th class="text-end">মোট অর্থ</th>
                    <th>পেমেন্ট পদ্ধতি</th>
                    <th>অ্যাকাউন্ট</th>
                    <th>স্ট্যাটাস</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0;
                @endphp
                @forelse($payments as $payment)
                    @php
                        $totalAmount += $payment->amount;
                    @endphp
                    <tr>
                        <td>{{ en2bn($loop->iteration) }}</td>
                        <td>{{ $payment->distribution->name ?? '-' }}</td>
                        <td>{{ en2bn(\Carbon\Carbon::parse($payment->date)->format('d-m-Y')) }}</td>
                        <td class="text-end">{{ en2bn(number_format($payment->amount, 2)) }}</td>
                        <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                        <td>{{ $payment->account->name ?? '-' }}</td>
                        <td>
                            @if($payment->status == 'Paid')
                                <span class="badge bg-success">পেড</span>
                            @elseif($payment->status == 'Pending')
                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                            @elseif($payment->status == 'Inactive')
                                <span class="badge bg-secondary">ইনএক্টিভ</span>
                            @else
                                <span class="badge bg-danger">ডিলিটেড</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.distributor-payments.show', $payment->id) }}" class="btn btn-sm btn-primary">বিস্তারিত দেখুন</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">কোনো পেমেন্ট পাওয়া যায়নি</td>
                    </tr>
                @endforelse
            </tbody>
            @if($payments->count())
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">সর্বমোট</th>
                    <th class="text-end">{{ en2bn(number_format($totalAmount, 2)) }}</th>
                    <th colspan="4"></th>
                </tr>
            </tfoot>
            @endif
        </table>
@endsection
