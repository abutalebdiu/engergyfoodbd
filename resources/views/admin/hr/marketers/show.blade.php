@extends('admin.layouts.app', ['title' => 'Show Employee Detail'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Employee Detail
                <a href="{{ route('admin.employee.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> Add
                    New Employee</a>
                <a href="{{ route('admin.employee.index') }}" class="btn btn-primary btn-sm float-end me-3"> <i
                        class="fa fa-list"></i>Employee List</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <tbody>
                        <tr>
                            <th style="width:10%">@lang('EMP ID')</th>
                            <td> {{ $employee->emp_id }} </td>

                            <th style="width:10%">@lang('Name')</th>
                            <td> {{ $employee->name }} </td>
                        </tr>
                        <tr>
                            <th>@lang('Designation')</th>
                            <td> {{ $employee->designation }}</td>

                            <th>@lang('Department')</th>
                            <td> {{ optional($employee->department)->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Email')</th>
                            <td> {{ $employee->email }}</td>

                            <th>@lang('Mobile')</th>
                            <td> {{ $employee->mobile }}</td>
                        </tr>

                        <tr>
                            <th>@lang('Joined At')</th>
                            <td> {{ $employee->joindate }}</td>

                            <th>@lang('Father')</th>
                            <td> {{ $employee->father }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Mother')</th>
                            <td> {{ $employee->mother }}</td>

                            <th>@lang('NID')</th>
                            <td> {{ $employee->nid }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Date of birth')</th>
                            <td> {{ $employee->dob }}</td>

                            <th>@lang('Address')</th>
                            <td> {{ $employee->address }}</td>
                        </tr>

                        <tr>
                            <th>@lang('Emergency Name')</th>
                            <td> {{ $employee->emergency_contact_name }}</td>

                            <th>@lang('Emergency')</th>
                            <td> {{ $employee->emergency_contact }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Education')</th>
                            <td> {{ $employee->education }}</td>

                            <th>@lang('CV Attachment')</th>
                            <td>
                                @if ($employee->attachment)
                                    <a href="{{ asset($employee->attachment) }}" class="btn btn-primary btn-sm">CV
                                        Download</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Status')</th>
                            <td> <span
                                    class="btn btn-{{ statusButton($employee->status) }} btn-sm">{{ $employee->status }}</span>
                            </td>

                            <th> @lang('Action') </th>
                            <td>

                                <a href="{{ route('admin.employee.edit', $employee->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                </a>

                            </td>
                        </tr>
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Salary Setup
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.salarysetup.store') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <div class="row">
                    @foreach ($salarytypes as $type)
                        <div class="col-12 col-md-6">
                            <div class="row mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="" class="form-label">{{ $type->name }}</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input type="hidden" name="salary_type_id[]" class="form-control"
                                        value="{{ $type->id }}">
                                    @php
                                        $getamount = App\Models\HR\SalarySetup::where('employee_id', $employee->id)
                                            ->where('salary_type_id', $type->id)
                                            ->get();
                                    @endphp
                                    @if ($getamount->count() > 0)
                                        <input type="number" name="amount[]" class="form-control"
                                            value="{{ $getamount[0]->amount }}">
                                    @else
                                        <input type="text" name="amount[]" class="form-control" value="0">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-12 col-md-6">
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="" class="form-label">Total</label>
                            </div>
                            <div class="col-12 col-md-8">
                                {{ $employee->salary() }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
