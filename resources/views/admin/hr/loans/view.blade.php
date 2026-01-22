@extends('admin.layouts.app', ['title' => 'Employee Loan List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Employee Loan List
                <a href="{{ route('admin.loan.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> Add New Loan</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>

                    <div class="col-12 col-md-3">
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
                            <th>@lang('Date')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Interest')</th>
                            <th>@lang('Total Amount')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Amount')</th>
                            <th>@lang('Monthly Installment')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td> {{ optional($item->employee)->name }} </td>
                                <td> {{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ en2bn(number_format($item->amount,2,'.',',')) }}</td>
                                <td> {{ en2bn(number_format($item->interest,2,'.',',')) }}</td>
                                <td> {{ en2bn(number_format($item->total_amount,2,'.',',')) }}</td>
                                <td> {{ en2bn(number_format($item->employee->loan_paid,2,'.',',')) }}</td>
                                <td> {{ en2bn(number_format($item->employee->loan_due,2,'.',',')) }}</td>
                                <td> {{ number_format($item->monthly_settlement) }}</td>
                                <td> {{ $item->note }}</td>
                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    {!! entry_info($item) !!}
                                </td>
                                <td>
                                    <a href="{{ route('admin.loan.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> @lang('Edit')
                                    </a>
                                    <a href="javascript:;" data-id="{{ $item->id }}" data-question="@lang('Are you sure you want to delete this item?')"
                                        data-action="{{ route('admin.loan.destroy', $item->id) }}" class="confirmationBtn">
                                        <i class="bi bi-trash"></i> @lang('Delete')
                                    </a>
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
                            <th colspan="6">@lang('Total')</th>
                            <th>{{ en2bn(number_format($loans->sum('amount'),2,'.',',')) }}</th>
                            <th>{{ en2bn(number_format($loans->sum('interest'),2,'.',',')) }}</th>
                            <th>{{ en2bn(number_format($loans->sum('total_amount'),2,'.',',')) }}</th>
                            <th colspan="5"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
