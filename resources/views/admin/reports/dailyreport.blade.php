@extends('admin.layouts.app', ['title' => __('Daily Reports')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Daily Reports')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" name="date" class="form-control"
                                @if (isset($date)) value="{{ $date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
                    </div>
                </div>
            </form>

            @if ($searching == 'Yes')
                <form method="post" action="{{ route('admin.reports.dailyreports.store') }}">
                @csrf
                    <div class="row mt-4">
                        <div class="col-12">
                            <p class=" mt-5">@lang('Date'): @if (isset($date)) {{ en2bn(Date('d-m-Y', strtotime($date))) }} @endif</p>
                            <input type="hidden" value="{{date('Y-m-d',strtotime($date))}}" name="date">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                     <tr >
                                        <th colspan="4" style="background-color:#000;color:#fff">Credit</th>
                                        <th colspan="4" style="background-color:#100;color:#fff">Debit</th>
                                    </tr>
                                    <tr>
                                        <th>@lang('SL No')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('No Financial')</th>
                                        <th>@lang('Financial')</th>
                                        <th>@lang('SL No')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('No Financial')</th>
                                        <th>@lang('Financial')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ en2bn('1') }}</td>
                                        <td class="text-start">Openging Cash Balance</td>
                                        <td>{{ en2bn(number_format($previousDateBalance, 2, '.', ',')) }}</td>
                                        <td></td>
                                        <td>{{ en2bn('1') }}</td>
                                        <td class="text-start">Sales Order Amount</td>
                                        <td class="text-end">{{ en2bn(number_format($salesamount, 2, '.', ',')) }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('1') }}</td>
                                        <td class="text-start">Sales Order Amount</td>
                                        <td class="text-end">{{ en2bn(number_format($salesamount, 2, '.', ',')) }}</td>
                                        <td></td>
                                        <td>{{ en2bn('1') }}</td>
                                        <td class="text-start">Item Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($itempayments, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('2') }}</td>
                                        <td class="text-start"> Sales Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($salepayments, 2, '.', ',')) }}</td>
                                        <td>{{ en2bn('2') }}</td>
                                        <td class="text-start">Expense Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($expensepayments, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('3') }}</td>
                                        <td class="text-start"></td>
                                        <td></td>
                                        <td class="text-end"></td>
    
                                        <td>{{ en2bn('3') }}</td>
                                        <td class="text-start">Salary Advance</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($salaryadvance, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('3') }}</td>
                                        <td class="text-start"> Customer Payment Receive</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($customerduepayment, 2, '.', ',')) }}</td>
    
                                        <td>{{ en2bn('3') }}</td>
                                        <td class="text-start">Supplier Due Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($supplierduepaymnet, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('4') }}</td>
                                        <td class="text-start">Deposit</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($deposit, 2, '.', ',')) }}</td>
                                        <td>{{ en2bn('4') }}</td>
                                        <td class="text-start">Salary Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($salarypayment, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('5') }}</td>
                                        <td class="text-start">Item Order Amount</td>
                                        <td class="text-end">{{ en2bn(number_format($itemorderamount, 2, '.', ',')) }}</td>
                                        <td></td>
                                        <td>{{ en2bn('5') }}</td>
                                        <td class="text-start">Employee Loan</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($loans, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('6') }}</td>
                                        <td class="text-start">Item Return Amount</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($itemreturns, 2, '.', ',')) }}</td>
                                        <td>{{ en2bn('6') }}</td>
                                        <td class="text-start">Office Loan Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($officialloanpayment, 2, '.', ',')) }}</td>
    
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('7') }}</td>
                                        <td class="text-start">Office Loan</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($officeloans, 2, '.', ',')) }}</td>
                                        <td>{{ en2bn('7') }}</td>
                                        <td class="text-start">Over Time Allowance</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($overtimeallowance, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('8') }}</td>
                                        <td class="text-start">Expense</td>
                                        <td class="text-end">{{ en2bn(number_format($expense, 2, '.', ',')) }}</td>
                                        <td></td>
                                        <td>{{ en2bn('8') }}</td>
                                        <td class="text-start"> Withdrawal </td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($withdrawal, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('9') }}</td>
                                        <td class="text-start"> Cash In Hand Balance</td>
                                        <td class="text-end"> {{ en2bn(number_format($cashaccount->balance($cashaccount->id), 2, '.', ',')) }} 
                                            <input type="hidden" name="account_balance" value="{{ $cashaccount->balance($cashaccount->id) }}">
                                        </td>
                                        <td></td>
                                        <td>{{ en2bn('9') }}</td>
                                        <td class="text-start"> Asset Expense Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($assetexpensepayment, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('10') }}</td>
                                        <td class="text-start">  </td>
                                        <td class="text-end">   </td>
                                        <td></td>
                                        <td>{{ en2bn('10') }}</td>
                                        <td class="text-start"> Monthly Expense Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($monthlyexpensepayment, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('11') }}</td>
                                        <td class="text-start">  </td>
                                        <td class="text-end">   </td>
                                        <td></td>
                                        <td>{{ en2bn('11') }}</td>
                                        <td class="text-start"> Transport Expense Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($transportexpensepayment, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('11') }}</td>
                                        <td class="text-start"> </td>
                                        <td class="text-end"> </td>
                                        <td></td>
                                        <td>{{ en2bn('11') }}</td>
                                        <td class="text-start">Marketer Commission Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($marketercommissionpayment, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ en2bn('12') }}</td>
                                        <td class="text-start"> </td>
                                        <td class="text-end"> </td>
                                        <td></td>
                                        <td>{{ en2bn('12') }}</td>
                                        <td class="text-start">Festival Bonus Payment</td>
                                        <td></td>
                                        <td class="text-end">{{ en2bn(number_format($festivalbonuspayment, 2, '.', ',')) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>@lang('Total')</th>
                                        <td colspan="3" class="text-end"> {{ en2bn(number_format($salepayments + $customerduepayment + $deposit + $officeloans, 2, '.', ','))   }} </td>
                                        <th></th>
                                        <td colspan="3" class="text-end"> {{ en2bn(number_format($itempayments + $expensepayments + $salaryadvance + $supplierduepaymnet + $salarypayment + $loans + $officialloanpayment + $overtimeallowance + $withdrawal + $assetexpensepayment + $monthlyexpensepayment + $transportexpensepayment + $marketercommissionpayment + $festivalbonuspayment, 2, '.', ',')) }} </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Day Closed</button>
                        </div>
                    </div>
                </form>
            @endif
 

        </div>

    </div>
@endsection
