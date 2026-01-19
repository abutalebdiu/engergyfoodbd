@extends('admin.layouts.app', ['title' => 'Edit Employee Salary'])
@section('panel')
    <form action="{{ route('admin.salarygenerate.update', $salarygenerate->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Employee Salary<a href="{{ route('admin.salarygenerate.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i>Employee Salary List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Employee') <span
                                    class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($employees as $item)
                                    <option {{ $salarygenerate->employee_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="month_id" id="month_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($months as $item)
                                    <option {{ $salarygenerate->month_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Year') <span
                                    class="text-danger">*</span></label>
                            <select name="year_id" id="year_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($years as $year)
                                    <option {{ $salarygenerate->year_id == $year->id ? 'selected' : '' }}
                                        value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Salary') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="salary"
                                value="{{ $salarygenerate->salary }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Per Day Salary') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="per_day_salary"
                                value="{{ $salarygenerate->per_day_salary }}" readonly>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Attendance') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="total_present"
                                value="{{ $salarygenerate->total_present }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Food Allowance') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="food_allowance"
                                value="{{ $salarygenerate->food_allowance }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Total Food Allowance') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="total_food_allowance"
                                value="{{ $salarygenerate->total_food_allowance }}" required>
                        </div>
                    </div>


                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Salary Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="salary_amount"
                                value="{{ $salarygenerate->salary_amount }}" required>
                        </div>
                    </div>


                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Advanced') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="advance_salary_amount"
                                value="{{ $salarygenerate->advance_salary_amount }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Bonus Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="bonus_amount"
                                value="{{ $salarygenerate->bonus_amount }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Loan Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="loan_amount"
                                value="{{ $salarygenerate->loan_amount }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Deduction Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="fine_amount"
                                value="{{ $salarygenerate->fine_amount }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Net Payble Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="payable_amount"
                                value="{{ $salarygenerate->payable_amount }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <input type="submit" class="btn btn-primary float-end"
                        onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                        value="@lang('Submit')">
                </div>
            </div>
        </div>
    </form>
@endsection
