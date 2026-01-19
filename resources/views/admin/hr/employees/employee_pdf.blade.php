<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Employee List')</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 0pt 30pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .products {
            float: left;
            width: 49%;
            margin-left: 2px;
        }
    </style>
</head>

<body>
    <div>
        <div class="print-header" style="text-align: center;margin-bottom:1px">
            <h4 style="margin: 0;padding:0;font-size:12pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
        </div>
        <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Employees')</h5>
        <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>

        <div class="table-responsive">
            <table border="1" style="width: 100%">
                <thead>
                    <tr>
                        <th>@lang('SL No')</th>
                        <th>@lang('EMP ID')</th>
                        <th style="width: 30%">@lang('Name')</th>
                        <th>@lang('Mobile')</th>
                        <th>@lang('Designation')</th>
                        <th>@lang('Salary')</th>
                        <th>@lang('Daily Salary')</th>
                        <th style="width: 10%">@lang('Food Allowance')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse($employeesByDepartment as $departmentId => $employees)
                        @php
                            $departmentName = optional($employees->first()->department)->name;
                            $totalSalary = $salarySums[$departmentId] ?? 0;
                            $totalFoodAllowance = $foodallowanceSums[$departmentId] ?? 0;
                        @endphp
                        <tr>
                            <td colspan="8" class="font-weight-bold text-primary text-start">
                                {{ $departmentName ?: 'No Department' }}
                            </td>
                        </tr>
                        @foreach ($employees as $index => $item)
                            <tr>
                                <td> {{ $i++ }} - {{ $loop->iteration }} </td>
                                <td> {{ $item->emp_id }} </td>
                                <td style="text-align: left;padding-left:5px"> {{ $item->name }} </td>
                                <td> {{ $item->mobile }}</td>
                                <td> {{ $item->designation }}</td>
                                <td> {{ en2bn(number_format($item->salary(), 0, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->daily_salary, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->food_allowance, 0, '.', ',')) }}</td>

                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                @lang('Total')
                            </td>
                            <td> {{ en2bn(number_format($totalSalary, 0, '.', ',')) }} </td>
                            <td></td>
                            <td> {{ en2bn(number_format($totalFoodAllowance, 0, '.', ',')) }} </td>

                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted" colspan="9">No Data Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
