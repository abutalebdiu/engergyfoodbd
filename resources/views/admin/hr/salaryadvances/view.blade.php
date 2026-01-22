@extends('admin.layouts.app', ['title' => 'Salary Advance List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Salary Advance list
                <a href="{{ route('admin.salaryadvance.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> Add New Salary Advance</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="department_id" id="department_id" class="form-select department_id">
                                <option value=""> -- @lang('Department') -- </option>
                                @foreach ($departments as $department)
                                    <option data-employees="{{ $department->employees }}" value="{{ $department->id }}">
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="month_id" id="month_id" class="form-select">
                                <option value=""> -- Select Month -- </option>
                                @foreach ($months as $item)
                                    <option {{ request()->month_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mt-2">
                        <div class="form-group">
                            <select name="year_id" id="year_id" class="form-select">
                                <option value=""> -- Select Year-- </option>
                                @foreach ($years as $year)
                                    <option {{ request()->year_id == $year->id ? 'selected' : '' }}
                                        value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mt-2">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryadvances as $monthYear => $departments)
                            <!-- Row for Month-Year -->
                            <tr>
                                <td colspan="11" class="text-start font-weight-bold">
                                    {{ $monthYear }}
                                </td>
                            </tr>

                            @foreach ($departments as $departmentName => $advances)
                                <!-- Row for Department -->
                                <tr>
                                    <td colspan="11" class="font-weight-bold text-start">
                                        @lang('Department'): {{ $departmentName ?? 'N/A' }}
                                    </td>
                                </tr>

                                @foreach ($advances as $item)
                                    <!-- Salary Advance Data -->
                                    <tr>
                                        <td>{{ $loop->parent->parent->iteration . '.' . $loop->parent->iteration . '.' . $loop->iteration }}
                                        </td>
                                        <td>{{ optional($item->employee)->name }}</td>
                                        <td>{{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                        <td>{{ optional($item->month)->name }} - {{ optional($item->year)->name }}</td>
                                        <td>{{ optional($item->paymentmethod)->name }}</td>
                                        <td>{{ optional($item->account)->title }}</td>
                                        <td style="text-align: right;padding-right:10px">
                                            {{ en2bn(number_format($item->amount, 2, '.', ',')) }}
                                        </td>
                                        <td>{{ $item->note }}</td>
                                        <td>
                                            <span class="btn btn-{{ statusButton($item->status) }} btn-sm">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>{!! entry_info($item) !!}</td>
                                        <td>
                                            <a href="{{ route('admin.salaryadvance.edit', $item->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i> @lang('Edit')
                                            </a>
                                            <a href="javascript:;" data-id="{{ $item->id }}"
                                                data-question="@lang('Are you sure you want to delete this item?')"
                                                data-action="{{ route('admin.salaryadvance.destroy', $item->id) }}"
                                                class="confirmationBtn">
                                                <i class="bi bi-trash"></i> @lang('Delete')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Total for Department -->
                                <tr>
                                    <td colspan="6" class="text-right font-weight-bold">@lang('Department Total')</td>
                                    <td style="text-align: right;padding-right:10px" class="font-weight-bold">
                                        {{ en2bn(number_format($advances->sum('amount'), 2, '.', ',')) }}
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="10">@lang('No Data Found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">@lang('Total')</th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($salaryadvances->flatten(2)->sum('amount'), 2, '.', ',')) }}
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
