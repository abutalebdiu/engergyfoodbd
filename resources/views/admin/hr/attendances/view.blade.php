@extends('admin.layouts.app', ['title' => 'Attendance list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                Attendance List
                <a href="{{ route('admin.attendance.create') }}" class="btn btn-sm btn-outline-primary float-end"> <i
                        class="fa fa-plus"></i> Add New
                    Attendance
                </a>
            </h4>
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
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Employee Name')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Year')</th>
                            <th>@lang('Days')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @forelse ($attendancesByYearMonth as $yearMonth => $attendances)
                            <!-- Display Year and Month -->
                            <tr>
                                <td colspan="8" class="text-start">Month: {{ $yearMonth }}</td>
                            </tr>

                            @php
                                // Group attendances by department within this year-month
                                $attendancesByDepartment = $attendances->groupBy('employee.department.name');
                            @endphp

                            @foreach ($attendancesByDepartment as $department => $employees)
                                <!-- Display Department -->
                                <tr>
                                    <td colspan="8" class="text-start">Department: {{ $department ?: 'No Department' }}
                                    </td>
                                </tr>

                                @foreach ($employees as $attendance)
                                    <tr>
                                        <td>{{ $i++ }} - {{ $loop->iteration }}</td>
                                        <td style="text-align: left"> {{ optional($attendance->employee)->name }} </td>
                                        <td> {{ optional($attendance->month)->name }} </td>
                                        <td> {{ optional($attendance->year)->name }} </td>
                                        <td> {{ $attendance->days }}</td>
                                        <td>
                                            <span class="btn btn-primary btn-sm">{{ $attendance->status }}</span>
                                        </td>
                                        <td>{{ optional($attendance->entryuser)->name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.attendance.edit', $attendance->id) }}">
                                                            <i class="bi bi-pencil"></i> @lang('Edit')
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-id="{{ $attendance->id }}"
                                                            data-question="@lang('Are you sure you want to delete this item?')"
                                                            data-action="{{ route('admin.attendance.destroy', $attendance->id) }}"
                                                            class="dropdown-item confirmationBtn">
                                                            <i class="bi bi-trash"></i> @lang('Delete')
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="8">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
