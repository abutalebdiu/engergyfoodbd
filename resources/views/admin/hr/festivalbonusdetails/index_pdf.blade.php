<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Order List')</title>
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Employee Festival Bonus List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Employee Name</th>
                            <th rowspan="2">Bonus Date</th>
                            <th rowspan="2">Date of Joining</th>
                            <th colspan="3">Length of Service</th>
                            <th rowspan="2">Salary</th>
                            <th rowspan="2">Basic</th>
                            <th colspan="2">Bonus</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Days</th>
                            <th>(%)</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $maintotalbonus = 0;
                        @endphp
                        @foreach ($festivalbonusdetailgroupes as $departmentId => $festivalbonusdetails)
                            @php
                                $departmentName = optional($festivalbonusdetails->first()->employee->department)->name;
                                $depttotalbonus = 0;
                            @endphp
                            <tr>
                                <td colspan="13" style="text-align:left;font-weight:bold">
                                    {{ $departmentName ?: 'No Department' }}
                                </td>
                            </tr>
                            @foreach ($festivalbonusdetails as $item)
                                <tr>
                                    <td>{{ en2bn($loop->iteration) }}</td>
                                    <td style="text-align: left">{{ optional($item->employee)->name }}</td>
                                    <td>{{ en2bn(Date('d-m-Y', strtotime($item->festivalbonus->date))) }}</td>
                                    <td>{{ en2bn(Date('d-m-Y', strtotime(optional($item->employee)->joindate))) }}</td>

                                    @php
                                        $festivalBonusDate = \Carbon\Carbon::parse($item->festivalbonus->date);
                                        $joinDate = \Carbon\Carbon::parse(optional($item->employee)->joindate);
                                        $diff = $joinDate->diff($festivalBonusDate);
                                        $serviceLength = "{$diff->y} Years, {$diff->m} Months, {$diff->d} Days";

                                        $depttotalbonus += $item->amount;
                                        $maintotalbonus += $item->amount;
                                    @endphp

                                    <td>{{ en2bn(number_format($diff->y)) }}</td>
                                    <td>{{ en2bn(number_format($diff->m)) }}</td>
                                    <td>{{ en2bn(number_format($diff->d)) }}</td>
                                    <td>{{ en2bn(number_format($item->salary_amount)) }}</td>
                                    <td>{{ en2bn(number_format($item->basic_amount)) }}</td>
                                    <td>{{ en2bn(number_format($item->bonus_percentage)) }}</td>
                                    <td style="text-align: right">{{ en2bn(number_format($item->amount)) }}</td>
                                    <td> {{ $item->status  }} </td>
                                    <td> </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="8"></th>
                                <th colspan="2">@lang('Total')</th>
                                <th style="text-align: right">{{ en2bn(number_format($depttotalbonus)) }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8"></th>
                            <th colspan="2">@lang('Total')</th>
                            <th style="text-align: right">{{ en2bn(number_format($maintotalbonus)) }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
