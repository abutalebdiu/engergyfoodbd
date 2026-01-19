@extends('admin.layouts.app', ['title' => 'Department Wise Salary List'])
@section('panel')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Department Wise Salary List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.salarygenerate.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> Salary Process</a>
        </div>
    </div>
    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">

            <form action="" method="">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select department_id select2">
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option
                                    @if (isset($department_id)) {{ $department_id == $department->id ? 'selected' : '' }} @endif
                                    value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pb-3 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Month')</label>
                            <select name="month_id" class="form-select select2">
                                <option value="">Select Month</option>
                                @foreach ($months as $month)
                                    <option
                                        @if (isset($month_id)) {{ $month_id == $month->id ? 'selected' : '' }} @endif
                                        value="{{ $month->id }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Year')</label>
                            <select name="year_id" class="form-select select2">
                                <option value="">Select Year</option>
                                @foreach ($years as $year)
                                    <option
                                        @if (isset($year_id)) {{ $year_id == $year->id ? 'selected' : '' }} @endif
                                        value="{{ $year->id }}">
                                        {{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-3">
                        <button class="btn btn-primary mt-4" name="search">
                            @lang('Search')
                        </button>
                        <button class="btn btn-primary mt-4" name="pdf">
                            @lang('PDF')
                        </button>
                    </div>
                </div>
            </form>


            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Department</th>
                            <th>Monthly Salary</th>
                            <th>Loan</th>
                            <th>Advance Taken</th>
                            <th>Bonus</th>
                            <th>Deduction</th>
                            <th>Payable Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaries as $department => $salaryGroup)
                            <tr>
                                <td colspan="8" class="text-center"><strong>Department: {{ $department }}</strong>
                                </td>
                            </tr>
                            @foreach ($salaryGroup as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($item->employee)->name }}</td>
                                    <td>{{ number_format($item->salary_amount, 2) }}</td>
                                    <td>{{ number_format($item->loan_amount, 2) }}</td>
                                    <td>{{ number_format($item->advance_salary_amount, 2) }}</td>
                                    <td>{{ number_format($item->bonus_amount, 2) }}</td>
                                    <td>{{ number_format($item->fine_amount, 2) }}</td>
                                    <td>{{ number_format($item->payable_amount, 2) }}</td>
                                </tr>
                            @endforeach
                            <!-- Department Totals -->
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total for {{ $department }}</strong></td>
                                <td>{{ number_format($departmentTotals[$department]['total_salary'], 2) }}</td>
                                <td>{{ number_format($departmentTotals[$department]['total_loan'], 2) }}</td>
                                <td>{{ number_format($departmentTotals[$department]['total_advance'], 2) }}</td>
                                <td>{{ number_format($departmentTotals[$department]['total_bonus'], 2) }}</td>
                                <td>{{ number_format($departmentTotals[$department]['total_deduction'], 2) }}</td>
                                <td>{{ number_format($departmentTotals[$department]['total_payable'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <!-- Grand Totals for all departments -->
                        <tr>
                            <td colspan="2" class="text-end"><strong>Grand Total</strong></td>
                            <td>{{ number_format($salaries->flatten()->sum('salary_amount'), 2) }}</td>
                            <td>{{ number_format($salaries->flatten()->sum('loan_amount'), 2) }}</td>
                            <td>{{ number_format($salaries->flatten()->sum('advance_salary_amount'), 2) }}</td>
                            <td>{{ number_format($salaries->flatten()->sum('bonus_amount'), 2) }}</td>
                            <td>{{ number_format($salaries->flatten()->sum('fine_amount'), 2) }}</td>
                            <td>{{ number_format($salaries->flatten()->sum('payable_amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>



        </div>
    </div>
@endsection
