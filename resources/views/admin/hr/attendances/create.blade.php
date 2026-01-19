@extends('admin.layouts.app', ['title' => 'Add New Attendance'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                Add New Attendance
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-outline-primary float-end">Attendance
                    List
                </a>
            </h5>
        </div>
        <div class="card-body">
            <form action="" method="">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select department_id select2" required>
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
                            <select name="month_id" class="form-select select2" required>
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
                            <select name="year_id" class="form-select select2" required>
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
                        <button class="btn btn-primary mt-4">
                            @lang('Search')
                        </button>
                    </div>
                    {{-- <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Days')</label>
                            <input type="text" class="form-control" name="days" value="{{ old('days') }}" required>
                        </div>
                    </div> --}}
                    {{-- <div class="col-12 col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Select Status</option>
                            <option {{ old('status') == 'Present' ? 'selected' : '' }} value="Present">Present</option>
                            <option {{ old('status') == 'Absent' ? 'selected' : '' }} value="Absent">Absent</option>
                            <option {{ old('status') == 'Leave' ? 'selected' : '' }} value="Leave">Leave</option>
                        </select>
                    </div> --}}

                </div>
            </form>
        </div>
    </div>

    @if ($searching == 'Yes')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.attendance.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="year_id" value="{{ $year_id }}">
                    <input type="hidden" name="month_id" value="{{ $month_id }}">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('SL')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Department')</th>
                                        <th>@lang('Present Day')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ en2bn($loop->iteration) }}</td>
                                            <td style="text-align: left;padding-left:10px">{{ $employee->name }} </td>
                                            <td style="text-align: left;padding-left:10px">{{ $employee->status }} </td>
                                            <td style="text-align: left;padding-left:10px">
                                                {{ optional($employee->department)->name }} </td>
                                            <td>
                                                <input type="hidden" name="employee_id[]" value="{{ $employee->id }}">
                                                <input type="number" class="form-control" name="days[]"
                                                    value="0" required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div class="col-12">
                            <a href="{{ route('admin.attendance.index') }}"
                                class="btn btn-outline-info float-start">Back</a>
                            <input type="submit" class="btn btn-primary float-end"
                                onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                value="@lang('Submit')">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@include('components.select2')
