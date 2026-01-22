@extends('admin.layouts.app', ['title' => 'Employee Over Time Allowance List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Employee Over Time Allowance list
                <a href="{{ route('admin.overtimeallowance.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> Add New Over Time Allowance</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="employee_id" id="employee_id" class="form-select select2 employee_id">
                                <option value=""> -- Select -- </option>
                                @foreach ($employees as $employee)
                                    <option  value="{{ $employee->id }}">
                                        {{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            Search</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            PDF</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            Excel</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimeallowances as $item)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i> Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.overtimeallowance.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-id="{{ $item->id }}"
                                                    data-question="@lang('Are you sure you want to delete this item?')"
                                                    data-action="{{ route('admin.overtimeallowance.destroy', $item->id) }}"
                                                    class="dropdown-item confirmationBtn">
                                                    <i class="bi bi-trash"></i> @lang('Delete')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td style="text-align:left"> {{ optional($item->employee)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ number_format($item->amount, 2) }}</td>
                                <td> {!! entry_info($item) !!} </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">@lang('Total')</th>
                            <th>{{ $overtimeallowances->sum('amount', 2) }}</th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

@include('components.select2')
