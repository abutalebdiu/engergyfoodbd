<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Summery</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            font-size: 14pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 0pt 30pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="header">
            <div class="print-header" style="text-align: center;margin-bottom:15px">
                <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                <p style="margin: 0;padding:0">{{ $general->address }}</p>
                <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
                <h5 style="margin: 0;padding:10px 0 0 ">@lang('Employee Payment Statement')</h5>
                <hr style="margin: 0;padding:0">
            </div>
        </div>
        <div class="main-body">
            <p>
                কর্মীর নামঃ {{ $employee->name }} <br>
                যোগদানের তারিখ: {{ Date('d-F-Y', strtotime($employee->joindate)) }}
            </p>


            <h5 style="margin: 0;padding:10px 0 0 ">Loans History</h5>
            <div class="table-responsive">
                <table border="1" style="width: 100%">
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
                                <td>{{ date('d-F-Y', strtotime($loan->date)) }}</td>
                                <td>{{ $loan->amount }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total Loan</th>
                            <td>{{ $loans->sum('amount') }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Paid</th>
                            <td>{{ $salarygenerates->sum('loan_amount') }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Due Loan</th>
                            <td style="font-weight: bold">
                                {{ $loans->sum('amount') - $salarygenerates->sum('loan_amount') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h5 style="margin: 0;padding:10px 0 0 ">Salary Generate History</h5>
            <div class="table-responsive">
                <table border="1" style="width: 100%">
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
            <h4 style="margin: 0;padding:10px 0 0 ">Salary Payment History</h4>
            <div class="table-responsive">
                <table border="1" style="width: 100%">
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
        <div class="footer"  style="margin-top: 50px">
             <p style="text-align: center;font-size:10px">এই রিপোর্টটি সফটওয়্যার দ্বারা স্বয়ংক্রিয়ভাবে প্রস্তুত করা হয়েছে।</p>
        </div>
    </div>

</body>

</html>
