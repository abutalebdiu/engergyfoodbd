@extends('admin.layouts.app', ['title' => 'Salary Payment History List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Salary Payment History List
                <a href="{{ route('admin.salarypaymenthistory.create') }}" class="btn btn-outline-primary btn-sm float-end">
                    <i class="bi bi-plus"></i>Pay Salary</a>

                <a href="{{ route('admin.salarypayment.single.employee.salary') }}"
                    class="btn btn-outline-success btn-sm float-end me-2">
                    <i class="bi bi-plus"></i>Single Pay Salary</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <select name="employee_id" id="employee_id" class="form-select select2">
                            <option value="">@lang('Search Employee')</option>
                            @foreach ($employees as $employee)
                                <option {{ request()->employee_id == $employee->id ? 'selected' : '' }}
                                    value="{{ $employee->id }}"> {{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
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
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $item)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.salarypaymenthistory.show', $item->id) }}">
                                                    <i class="bi bi-eye-fill"></i> Show
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.salarypaymenthistory.edit', $item->id) }}"
                                                    class="dropdown-item">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-id="{{ $item->id }}"
                                                    data-question="@lang('Are you sure you want to delete this item?')"
                                                    data-action="{{ route('admin.salarypaymenthistory.destroy', $item->id) }}"
                                                    class="dropdown-item confirmationBtn">
                                                    <i class="bi bi-trash"></i> @lang('Delete')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td> {{ optional($item->employee)->name }} </td>
                                <td> {{ optional(optional($item->salarygenerate)->month)->name }} -
                                    {{ optional(optional($item->salarygenerate)->year)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }}</td>

                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7">@lang('Total')</th>
                            <th> {{ en2bn(number_format($histories->sum('amount'), 2)) }}</th>
                            <th></th>

                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
