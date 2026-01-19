@extends('admin.layouts.app', ['title' => 'Employee Payment History'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Employee Payment History
                <a href="{{ route('admin.employee.payment.history.pdf',$employee->id) }}" class="float-end btn btn-primary btn-sm"> <i class="fa fa-download"></i> Download</a>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <p>
                        কর্মীর নামঃ {{ $employee->name }} <br>
                        যোগদানের তারিখ: {{ Date('d-F-Y', strtotime($employee->joindate)) }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-5">
                    <h5>Loans</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ date('d-F-Y', strtotime($loan->date)) }}</td>
                                        <td>{{ $loan->amount }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2">Total Loan</th>
                                    <th>{{  $loans->sum('amount') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Paid</th>
                                    <td>{{ $salarygenerates->sum('loan_amount') }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Due Loan</th>
                                    <td>{{ $loans->sum('amount') - $salarygenerates->sum('loan_amount') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <h5>Salary Generate</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Month')</th>
                                    <th>@lang('Monthly Salary')</th>
                                    <th>@lang('Per Day')</th>
                                    <th>@lang('Total Present')</th>
                                    <th>@lang('Food Allowance Day')</th>
                                    <th>@lang('Total Food Allowance')</th>
                                    <th>@lang('Total Work')</th>
                                    <th>@lang('Total Salary')</th>
                                    <th>@lang('Loan Adjustment')</th>
                                    <th>@lang('Advanced Taken')</th>
                                    <th>@lang('Bonus')</th>
                                    <th>@lang('Deduction')</th>
                                    <th>@lang('Payable Salary')</th>
                                    <th>@lang('Due Loan')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salarygenerates as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                                        <td>{{ en2bn(number_format($item->salary)) }}</td>
                                        <td>{{ en2bn(number_format($item->per_day_salary)) }}</td>
                                        <td>{{ $item->total_present }}</td>
                                        <td>{{ en2bn(number_format($item->food_allowance)) }}</td>
                                        <td>{{ en2bn(number_format($item->total_food_allowance)) }}</td>
                                        <td>{{ $item->total_present }}</td>
                                        <td>{{ en2bn(number_format($item->salary_amount)) }}</td>
                                        <td>{{ en2bn(number_format($item->loan_amount)) }}</td>
                                        <td>{{ en2bn(number_format($item->advance_salary_amount)) }}</td>
                                        <td>{{ en2bn(number_format($item->bonus_amount)) }}</td>
                                        <td>{{ en2bn(number_format($item->fine_amount)) }}</td>
                                        <td>{{ en2bn(number_format($item->payable_amount)) }}</td>
                                        <td>{{ en2bn(number_format($item->due_loan ?? 0)) }} </td>
                                        <td>
                                            <span
                                                class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="14">@lang('Total')</th>
                                    <th>{{ en2bn(number_format($salarygenerates->sum('payable_amount'))) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <h5>Salary Payments</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Month')</th>
                                    <th>@lang('Payment Method')</th>
                                    <th>@lang('Account')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salarypayments as $item)
                                    <tr>
                                        <td> {{ en2bn($loop->iteration) }} </td>
                                        <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                        <td> {{ optional(optional($item->salarygenerate)->month)->name }} -
                                            {{ optional(optional($item->salarygenerate)->year)->name }} </td>
                                        <td> {{ optional($item->paymentmethod)->name }} </td>
                                        <td> {{ optional($item->account)->title }} </td>
                                        <td> {{ en2bn(number_format($item->amount)) }}</td>
                                        <td> <span
                                                class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">@lang('Total')</th>
                                    <th>{{ en2bn(number_format($salarypayments->sum('amount'))) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
