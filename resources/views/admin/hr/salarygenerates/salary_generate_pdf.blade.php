<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Salary List')</title>
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
        <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Salary Sheet')</h5>
        <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>

        <table style="width: 100%" border="1">
            <thead>
                <tr>
                    <th>@lang('SL')</th>
                    <th>@lang('EMP Name')</th>
                    <th>@lang('Join Date')</th>
                    <th>@lang('Month')</th>
                    <th>@lang('Monthly Salary')</th>
                    <th>@lang('Per Day')</th>
                    <th>@lang('Total Present')</th>
                    <th>@lang('Food Allowance Day')</th>
                    <th>@lang('Total Food Allowance')</th>
                    <th>@lang('Total Work')</th>
                    <th>@lang('Total Salary')</th>
                    <th>@lang('Total Loan')</th>
                    <th>@lang('Loan Adjustment')</th>
                    <th>@lang('Advanced Taken')</th>
                    <th>@lang('Bonus')</th>
                    <th>@lang('Deduction')</th>
                    <th>@lang('Payable Salary')</th>
                    <th>@lang('Due Loan')</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=1;
                    // Initialize grand total variables
                    $grandTotalSalary = 0;
                    $grandTotalPerDay = 0;
                    $grandTotalPresent = 0;
                    $grandTotalFoodAllowance = 0;
                    $grandTotalSalaryAmount = 0;
                    $grandTotalWork = 0;
                    $grandTotalLoan = 0;
                    $grandTotalLoanAdjustment = 0;
                    $grandTotalAdvanced = 0;
                    $grandTotalBonus = 0;
                    $grandTotalDeduction = 0;
                    $grandTotalPayable = 0;
                    $grandTotalLoanDue = 0;

                    // Extract the first item to get the common month and year
                    $firstItem = $salarygeneratesByDepartment->first()->first();
                    $monthName = optional($firstItem->month)->name;
                    $yearName = optional($firstItem->year)->name;
                @endphp

                {{-- Month and Year row --}}
                <tr>
                    <td colspan="18" class="font-weight-bold text-center text-info">
                        @lang('Month:') {{ $monthName }} - @lang('Year:') {{ $yearName }}
                    </td>
                </tr>

                @forelse($salarygeneratesByDepartment as $departmentName => $salarygenerates)
                    @php
                        // Initialize department total variables
                        $totalSalary = 0;
                        $totalPerDay = 0;
                        $totalPresent = 0;
                        $totalFoodAllowance = 0;
                        $totalSalaryAmount = 0;
                        $totalWork = 0;
                        $totalLoan = 0;
                        $totalLoanAdjustment = 0;
                        $totalAdvanced = 0;
                        $totalBonus = 0;
                        $totalDeduction = 0;
                        $totalPayable = 0;
                        $totalLoanDue = 0;
                    @endphp

                    <tr>
                        <td colspan="18" class="font-weight-bold text-start text-primary">{{ $departmentName }}</td>
                        {{-- Show department name --}}
                    </tr>

                    @foreach ($salarygenerates as $index => $item)
                        @php
                            // Add current item's values to the department totals
                            $totalSalary += $item->salary;
                            $totalPerDay += $item->per_day_salary;
                            $totalPresent += $item->total_present;
                            $totalFoodAllowance += $item->total_food_allowance;
                            $totalSalaryAmount += $item->salary_amount;
                            $totalWork += $item->total_present;
                            $totalLoan += optional($item->employee)->loan_amount ?? 0;
                            $totalLoanAdjustment += $item->loan_amount;
                            $totalAdvanced += $item->advance_salary_amount;
                            $totalBonus += $item->bonus_amount;
                            $totalDeduction += $item->fine_amount;
                            $totalPayable +=  $item->payable_amount;
                            $totalLoanDue +=  $item->due_loan ?? 0;

                            // Add to grand totals
                            $grandTotalSalary += $item->salary;
                            $grandTotalPerDay += $item->per_day_salary;
                            $grandTotalPresent += $item->total_present;
                            $grandTotalFoodAllowance += $item->total_food_allowance;
                            $grandTotalSalaryAmount += $item->salary_amount;
                            $grandTotalWork += $item->total_present;
                            $grandTotalLoan += optional($item->employee)->loan_amount ?? 0;
                            $grandTotalLoanAdjustment += $item->loan_amount;
                            $grandTotalAdvanced += $item->advance_salary_amount;
                            $grandTotalBonus += $item->bonus_amount;
                            $grandTotalDeduction += $item->fine_amount;
                            $grandTotalPayable += $item->payable_amount;
                            $grandTotalLoanDue +=  $item->due_loan ?? 0;
                        @endphp

                        <tr>
                            <td>{{ $i++ }} - {{ $loop->iteration }} </td>
                            <td>{{ optional($item->employee)->name }} </td>
                            <td>{{ optional($item->employee)->joindate }} </td>
                            <td>{{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                            <td>{{ $item->salary }}</td>
                            <td>{{ $item->per_day_salary }}</td>
                            <td>{{ $item->total_present }}</td>
                            <td>{{ $item->food_allowance }}</td>
                            <td>{{ $item->total_food_allowance }}</td>
                            <td>{{ $item->total_present }}</td>
                            <td>{{ $item->salary_amount }}</td>
                            <td>{{ optional($item->employee)->loan_amount ?? 0 }} </td>
                            <td>{{ $item->loan_amount }}</td>
                            <td>{{ $item->advance_salary_amount }}</td>
                            <td>{{ $item->bonus_amount }}</td>
                            <td>{{ $item->fine_amount }}</td>
                            <td>{{ $item->payable_amount }}</td>
                            <td>{{ $item->due_loan ?? 0 }} </td>
                        </tr>
                    @endforeach

                    {{-- Department totals row --}}
                    <tr>
                        <td colspan="4" class="text-end font-weight-bold">@lang('Total for Department: ')
                            {{ $departmentName }}</td>
                        <td>{{ number_format($totalSalary, 2) }}</td>
                        <td>{{ number_format($totalPerDay, 2) }}</td>
                        <td>{{ $totalPresent }}</td>
                        <td>-</td> {{-- Food allowance per day (static column) --}}
                        <td>{{ number_format($totalFoodAllowance, 2) }}</td>
                        <td>{{ $totalWork }}</td>
                        <td>{{ number_format($totalSalaryAmount,2) }}</td> {{-- Salary amount (static column) --}}
                        <td>{{ number_format($totalLoan, 2) }}</td>
                        <td>{{ number_format($totalLoanAdjustment, 2) }}</td>
                        <td>{{ number_format($totalAdvanced, 2) }}</td>
                        <td>{{ number_format($totalBonus, 2) }}</td>
                        <td>{{ number_format($totalDeduction, 2) }}</td>
                        <td>{{ number_format($totalPayable, 2) }}</td>
                        <td>{{ number_format($totalLoanDue, 2) }}</td>

                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-muted" colspan="20">No Data Found</td>
                    </tr>
                @endforelse
            </tbody>

            {{-- Grand totals in tfoot --}}
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end font-weight-bold">@lang('Grand Total')</td>
                    <td>{{ number_format($grandTotalSalary, 2) }}</td>
                    <td>{{ number_format($grandTotalPerDay, 2) }}</td>
                    <td>{{ $grandTotalPresent }}</td>
                    <td>-</td> {{-- Food allowance per day (static column) --}}
                    <td>{{ number_format($grandTotalFoodAllowance, 2) }}</td>
                    <td>{{ $grandTotalWork }}</td>
                    <td>{{ number_format($grandTotalSalaryAmount,2) }}</td> {{-- Salary amount (static column) --}}
                    <td>{{ number_format($grandTotalLoan, 2) }}</td>
                    <td>{{ number_format($grandTotalLoanAdjustment, 2) }}</td>
                    <td>{{ number_format($grandTotalAdvanced, 2) }}</td>
                    <td>{{ number_format($grandTotalBonus, 2) }}</td>
                    <td>{{ number_format($grandTotalDeduction, 2) }}</td>
                    <td>{{ number_format($grandTotalPayable, 2) }}</td>
                    <td>{{ number_format($grandTotalLoanDue, 2) }}</td>
                </tr>
            </tfoot>
        </table>

    </div>
</body>

</html>
