<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Cash Register')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 14px;
        }

        table td {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div>
        <div class="wrapper">
            <div class="print-header" style="text-align: center;margin-bottom:15px">
                <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                <p style="margin: 0;padding:0">{{ $general->address }}</p>
                <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
            </div>
            <h5 style="text-align: center;margin: 0;padding:0;border-bottom:1px solid #000">@lang('Cash Register')</h5>
            
            <div class="product-detail">
                @if ($searching == 'Yes')
                    <div class="row mt-4">
                        <div class="col-12">
                             
                            <p style="margin: 0;padding:0">@lang('Date'):
                                @if (isset(request()->start_date))
                                    {{ en2bn(Date('d-m-Y', strtotime(request()->start_date))) }}
                                @endif
                                হতে
                                @if (isset(request()->end_date))
                                    {{ en2bn(Date('d-m-Y', strtotime(request()->end_date))) }}
                                @endif পর্যন্ত
                            </p>

                            <table border="1">
                                 <thead class="table-dark">
                                    <tr>
                                        <th rowspan="2">Date</th>
                                        <th colspan="4">Income</th>
                                        <th colspan="13">Expense</th>
                                        <th>Balance</th>
                                    </tr>
                                    <tr>
                                        <th>Sales</th>
                                        <th>Customer Due Receive</th>
                                        <th>Deposit</th>
                                        <th>Office Loan</th>
        
                                        <th>Item Order Payment</th>
                                        <th>Supplier Due Payment</th>
        
                                        <th>Expense</th>
                                        <th>Asset Expense</th>
                                        <th>Monthly Expense</th>
                                        <th>Transport Expense</th>
        
        
                                        <th>Salary</th>
                                        <th>Advance</th>
                                        <th>Loan</th>
                                        <th>Overtime Allowance</th>
        
                                        <th>Withdraw</th>
                                        <th>Office Loan Payment</th>
                                        <th>Marketer Commission</th>
                                        <th>Balance</th>
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="18">Opending Balance</td>
                                        <td>{{ number_format($opening_balance,2) }}</td>
                                    </tr>
                                    @foreach ($cashRegisters as $row)
                                        <tr>
                                            <td>{{ en2bn(date('d-m-Y', strtotime($row['date']))) }}</td>
        
                                            <td>{{ number_format($row['sales_payment'], 2) }}</td>
                                            <td>{{ number_format($row['customer_due'], 2) }}</td>
                                            <td>{{ number_format($row['deposit'], 2) }}</td>
                                            <td>{{ number_format($row['office_loan'], 2) }}</td>
        
                                            <td>{{ number_format($row['item_payment'], 2) }}</td>
                                            <td>{{ number_format($row['supplier_payment'], 2) }}</td>
        
        
                                            <td>{{ number_format($row['expense_payment'], 2) }}</td>
                                            <td>{{ number_format($row['asset_expensepayment'], 2) }}</td>
                                            <td>{{ number_format($row['monthly_expensepayment'], 2) }}</td>
                                            <td>{{ number_format($row['transport_expensepayment'], 2) }}</td>
        
                                            <td>{{ number_format($row['salary_payment'], 2) }}</td>
                                            <td>{{ number_format($row['salary_advance'], 2) }}</td>
                                            <td>{{ number_format($row['loan'], 2) }}</td>
                                            <td>{{ number_format($row['overtime_allowance'], 2) }}</td>
        
                                            <td>{{ number_format($row['withdrawal'], 2) }}</td>
                                            <td>{{ number_format($row['office_loan_payment'], 2) }}</td>
                                            <td>{{ number_format($row['marketer_commission_payment'], 2) }}</td>
                                            <td class="fw-bold text-end">
                                                {{ number_format($row['balance'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                {{-- ===== FOOTER TOTAL ===== --}}
                                <tfoot class="table-secondary fw-bold">
                                    <tr>
                                        <th>Total</th>
        
                                        <th>{{ number_format($total_sales_payment, 2) }}</th>
                                        <th>{{ number_format($total_customer_due, 2) }}</th>
                                        <th>{{ number_format($total_deposit, 2) }}</th>
                                        <th>{{ number_format($total_office_loan, 2) }}</th>
        
                                        <th>{{ number_format($total_item_payment, 2) }}</th>
                                        <th>{{ number_format($total_supplier_payment, 2) }}</th>
        
                                        <th>{{ number_format($total_expense_payment, 2) }}</th>
                                        <th>{{ number_format($total_asset_expensepayment, 2) }}</th>
                                        <th>{{ number_format($total_monthly_expensepayment, 2) }}</th>
                                        <th>{{ number_format($total_transport_expensepayment, 2) }}</th>
        
        
                                        <th>{{ number_format($total_salary_payment, 2) }}</th>
                                        <th>{{ number_format($total_salary_advance, 2) }}</th>                               
                                        <th>{{ number_format($total_loan, 2) }}</th>
                                         <th>{{ number_format($total_overtime_allowance, 2) }}</th>
        
                                        <th>{{ number_format($total_withdrawal, 2) }}</th>
                                        <th>{{ number_format($total_office_loan_payment, 2) }}</th>
                                        <th>{{ number_format($total_marketer_commission_payment, 2) }}</th>
                                        <th>{{ number_format($closing_balance, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
</body>

</html>
