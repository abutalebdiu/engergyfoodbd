@if ($salarygeneratesByDepartment->count() > 0)
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
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
                <th>@lang('Status')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @php
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
                <td colspan="20" class="font-weight-bold text-start text-info">
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
                    <td colspan="20" class="font-weight-bold text-start text-primary">
                        {{ $departmentName }}
                    </td>
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
                        $totalPayable += $item->payable_amount;
                        $totalLoanDue += $item->due_loan ?? 0;

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
                        $grandTotalLoanDue += $item->due_loan ?? 0;
                    @endphp

                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ optional($item->employee)->name }} </td>
                        <td> {{ optional($item->employee)->joindate }} </td>
                        <td> {{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                        <td>{{ $item->salary }}</td>
                        <td>{{ $item->per_day_salary }}</td>
                        <td>{{ $item->total_present }}</td>
                        <td>{{ $item->food_allowance }}</td>
                        <td>{{ $item->total_food_allowance }}</td>
                        <td>{{ $item->total_present }}</td>
                        <td>{{ $item->salary_amount }}</td>
                        <td> {{ optional($item->employee)->loan_amount ?? 0 }} </td>
                        <td>{{ $item->loan_amount }}</td>
                        <td>{{ $item->advance_salary_amount }}</td>
                        <td>{{ $item->bonus_amount }}</td>
                        <td>{{ $item->fine_amount }}</td>
                        <td>{{ $item->payable_amount }}</td>
                        <td>{{ $item->due_loan ?? 0 }} </td>
                        <td>
                            <span
                                class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.salarygenerate.edit', $item->id) }}"
                                            class="dropdown-item">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-id="{{ $item->id }}"
                                            data-question="@lang('Are you sure you want to delete this item?')"
                                            data-action="{{ route('admin.salarygenerate.destroy', $item->id) }}"
                                            class="dropdown-item confirmationBtn">
                                            <i class="bi bi-trash"></i> @lang('Delete')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
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
                    <td>{{ $totalSalaryAmount }}</td> {{-- Salary amount (static column) --}}
                    <td>{{ number_format($totalLoan, 2) }}</td>
                    <td>{{ number_format($totalLoanAdjustment, 2) }}</td>
                    <td>{{ number_format($totalAdvanced, 2) }}</td>
                    <td>{{ number_format($totalBonus, 2) }}</td>
                    <td>{{ number_format($totalDeduction, 2) }}</td>
                    <td>{{ number_format($totalPayable, 2) }}</td>
                    <td>{{ number_format($totalLoanDue, 2) }}</td>
                    <td>-</td> {{-- Status column (static column) --}}
                    <td>-</td> {{-- Action column (static column) --}}
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
                <td>{{ $grandTotalSalaryAmount }}</td> {{-- Salary amount (static column) --}}
                <td>{{ number_format($grandTotalLoan, 2) }}</td>
                <td>{{ number_format($grandTotalLoanAdjustment, 2) }}</td>
                <td>{{ number_format($grandTotalAdvanced, 2) }}</td>
                <td>{{ number_format($grandTotalBonus, 2) }}</td>
                <td>{{ number_format($grandTotalDeduction, 2) }}</td>
                <td>{{ number_format($grandTotalPayable, 2) }}</td>
                <td>{{ number_format($grandTotalLoanDue, 2) }}</td>
                <td>-</td> {{-- Status column (static column) --}}
                <td>-</td> {{-- Action column (static column) --}}
            </tr>
        </tfoot>
    </table>
</div>
@endif