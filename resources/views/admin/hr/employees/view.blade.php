@extends('admin.layouts.app', ['title' => 'Employee List'])
@section('panel')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Employee List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.employee.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> @lang('Add New')</a>
        </div>
    </div>
    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">
            <form action="" method="get">
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option
                                        @if (isset($department_id)) {{ $department_id == $department->id ? 'selected' : '' }} @endif
                                        value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <select name="status" id="status" class="form-select">
                                <option value="">Select Status</option>
                                <option {{ request()->status == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                                <option {{ request()->status == 'Inactive' ? 'selected' : '' }} value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button class="btn btn-primary" name="search">
                            <i class="fa fa-search"></i> @lang('Search')
                        </button>
                        <button class="btn btn-primary" name="pdf">
                            <i class="fa fa-download"></i> @lang('PDF')
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('SL No')</th>
                            <th>@lang('EMP ID')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Designation')</th>
                            <th>@lang('Joined At')</th>
                            <th>@lang('Salary')</th>
                            <th>@lang('Daily Salary')</th>
                            <th>@lang('Food Allowance')</th>
                            <th>@lang('Total Loan')</th>
                            <th>@lang('Loan Paid')</th>
                            <th>@lang('Loan Due')</th>
                            <th>@lang('Month Installment')</th>
                            <th>@lang('Bonus Eligibility')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            $totaldueloan = 0;
                            $totalsalary = 0;
                        @endphp
                        @forelse($employeesByDepartment as $departmentId => $employees)
                            @php
                                $departmentName = optional($employees->first()->department)->name;
                                $totalSalary = $salarySums[$departmentId] ?? 0;
                                $totalFoodAllowance = $foodallowanceSums[$departmentId] ?? 0;
                                $totalLoanDue = $empLoanDueAmountSums[$departmentId] ?? 0;

                                $totaldueloan += $totalLoanDue;
                                $totalsalary  += $totalSalary;
                            @endphp
                            <tr>
                                <td colspan="16" class="font-weight-bold text-primary text-start">
                                    {{ $departmentName ?: 'No Department' }}
                                </td>
                            </tr>
                            @foreach ($employees as $index => $item)
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.employee.show', $item->id) }}">
                                                        <i class="bi bi-eye-fill"></i> Show
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.employee.payment.history', $item->id) }}"
                                                        class="dropdown-item">
                                                        <i class="bi bi-cash"></i> Payment History
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.employee.payment.history.pdf', $item->id) }}"
                                                        class="dropdown-item">
                                                        <i class="fa fa-download"></i> Statement Download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.employee.edit', $item->id) }}"
                                                        class="dropdown-item">
                                                        <i class="bi bi-pencil-fill"></i> Edit
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="javascript:;" data-id="{{ $item->id }}"
                                                        data-question="@lang('Are you sure you want to delete this item?')"
                                                        data-action="{{ route('admin.employee.destroy', $item->id) }}"
                                                        class="dropdown-item confirmationBtn">
                                                        <i class="bi bi-trash"></i> @lang('Delete')
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->status == 'Active')
                                            <a href="{{ route('admin.employee.status.change', $item->id) }}"
                                                onclick="return confirm('@lang('Are you sure! Inactive this Employee')')"
                                                class="btn btn-success btn-sm">@lang('Active')</a>
                                        @else
                                            <a href="{{ route('admin.employee.status.change', $item->id) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('@lang('Are you sure! Active this Employee')')">@lang('Inactive')</a>
                                        @endif
                                    </td>
                                    <td> {{ $i++ }} - {{ $loop->iteration }} </td>
                                    <td> {{ $item->emp_id }} </td>
                                    <td style="text-align: left;padding-left:5px"> {{ $item->name }} </td>
                                    <td> {{ $item->mobile }}</td>
                                    <td> {{ $item->designation }}</td>
                                    <td> {{ $item->joindate }}</td>
                                    <td> {{ en2bn(number_format($item->salary(), 0, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->daily_salary, 0, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->food_allowance, 0, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->totalloan($item->id), 0, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->paidloan($item->id), 0, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->receiableloan($item->id), 0, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->loan_installment, 0, '.', ',')) }}</td>
                                    <td> {{ $item->bonus_eligibility }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    @lang('Total')
                                </td>
                                <td> {{ en2bn(number_format($totalSalary, 0, '.', ',')) }} </td>
                                <td></td>
                                <td> {{ en2bn(number_format($totalFoodAllowance, 0, '.', ',')) }} </td>
                                <td></td>
                                <td></td>
                                <td> {{ en2bn(number_format($totalLoanDue, 0, '.', ',')) }} </td>
                                <td colspan="2"></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="11">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8">@lang('Total')</th>
                            <th>{{ en2bn(number_format($totalsalary, 0, '.', ',')) }}</th>
                            <th colspan="4"></th>
                            <th>{{ en2bn(number_format($totaldueloan, 0, '.', ',')) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-3">
                {{ $pagination->withQueryString()->links() }}
            </div>
            
        </div>

    </div>

    <x-destroy-confirmation-modal />
@endsection
