@extends('admin.layouts.app', ['title' => 'Edit Attendance'])
@section('panel')
    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-outline-primary">Attendance List
            </a>
        </div>
    @endpush

    <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select select2" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option {{ $attendance->employee_id == $employee->id ? 'selected' : '' }}
                                    value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Month')</label>
                            <select name="month_id" class="form-select select2" required>
                                <option value="">Select Month</option>
                                @foreach ($months as $month)
                                    <option {{ $attendance->month_id == $month->id ? 'selected' : '' }}
                                        value="{{ $month->id }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Year')</label>
                            <select name="year_id" class="form-select select2" required>
                                <option value="">Select Year</option>
                                @foreach ($years as $year)
                                    <option {{ $attendance->year_id == $year->id ? 'selected' : '' }} value="{{ $year->id }}">
                                        {{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Days')</label>
                            <input type="text" class="form-control" name="days" value="{{ $attendance->days }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Select Status</option>
                            <option {{ $attendance->status == 'Present' ? 'selected' : '' }} value="Present">Present</option>
                            <option {{ $attendance->status == 'Absent' ? 'selected' : '' }} value="Absent">Absent</option>
                            <option {{ $attendance->status == 'Leave' ? 'selected' : '' }} value="Leave">Leave</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <input type="submit" class="btn btn-primary float-end"
                        onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                        value="@lang('Submit')">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
