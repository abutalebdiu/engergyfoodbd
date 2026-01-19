<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Employee Salary Advance List')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 12px;
        }

        table td {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div>
        <div class="wrapper">
            <div class="print-header" style="text-align: center;margin-bottom:15px">
                <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                <p style="margin: 0;padding:0">{{ $general->address }}</p>
                <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
            </div>
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Employee Salary Advance List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryadvances as $monthYear => $departments)
                            <!-- Row for Month-Year -->
                            <tr>
                                <td colspan="9" style="text-align:left">
                                    {{ $monthYear }}
                                </td>
                            </tr>
                
                            @foreach($departments as $departmentName => $advances)
                                <!-- Row for Department -->
                                <tr>
                                    <td colspan="9" style="text-align:left">
                                        @lang('Department'): {{ $departmentName ?? 'N/A' }}
                                    </td>
                                </tr>
                
                                @foreach($advances as $item)
                                    <!-- Salary Advance Data -->
                                    <tr>
                                        <td>{{ $loop->parent->parent->iteration . '.' . $loop->parent->iteration . '.' . $loop->iteration }}</td>
                                        <td>{{ optional($item->employee)->name }}</td>
                                        <td>{{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                        <td>{{ optional($item->month)->name }} - {{ optional($item->year)->name }}</td>
                                        <td>{{ optional($item->paymentmethod)->name }}</td>
                                        <td>{{ optional($item->account)->title }}</td>
                                        <td style="text-align: right;padding-right:10px">
                                            {{ en2bn(number_format($item->amount, 2, '.', ',')) }}
                                        </td>
                                        <td>{{ $item->note }}</td>
                                        <td>
                                            <span class="btn btn-{{ statusButton($item->status) }} btn-sm">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                
                                <!-- Total for Department -->
                                <tr>
                                    <td colspan="6"  style="text-align:right;padding-right:10px">@lang('Department Total')</td>
                                    <td style="text-align: right;padding-right:10px" class="font-weight-bold">
                                        {{ en2bn(number_format($advances->sum('amount'), 2, '.', ',')) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="10">@lang('No Data Found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">@lang('Total')</th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($salaryadvances->flatten(2)->sum('amount'), 2, '.', ',')) }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>
