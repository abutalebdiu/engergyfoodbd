<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Summery Report')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 16px;
        }

        table td {
            font-size: 15px;
        }

        .col-md-3 {
            width: 20%;
            float: left;
        }

        h6 {
            padding: 0;
            margin: 0;
        }

        .card {
            padding: 5px;
            text-align: left;

        }

        .card-body {
            padding: 5px;
            box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
        }

        .text-success {
            color: green;
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Day Wise Summery / Summery Report')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    @include('admin.reports.includes.summery_report_table')
                </table>
                <br>
            </div>
            <div class="row mt-4">
                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Order Amount')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_net_amount']) ? number_format($datas['total_net_amount'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Customers Commission')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_customer_commission']) ? number_format($datas['total_customer_commission'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>



                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Marketer Commission')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_marketer_commission']) ? number_format($datas['total_marketer_commission'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Orders Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_paid_amount']) ? number_format($datas['total_paid_amount'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Customer Due Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(number_format(isset($datas['total_customer_due_payment']) ? $datas['total_customer_due_payment'] : 0, 2)) }}
                            </h6>
                        </div>
                    </div>
                </div>



                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Order Due')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_main_order_due_amount']) ? number_format($datas['total_main_order_due_amount'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Receivable Customer Due')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_order_due']) ? number_format($datas['total_order_due'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Item Order')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_item_order']) ? number_format($datas['total_item_order'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Item Orders Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_item_order_payments']) ? number_format($datas['total_item_order_payments'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Supplier Due Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_supplier_due_payment']) ? number_format($datas['total_supplier_due_payment'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Supplier Payable Amount')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_supplier_due_amount']) ? number_format($datas['total_supplier_due_amount'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Overall Supplier Payable Amount')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_supplier_payable_amount']) ? number_format($datas['total_supplier_payable_amount'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Total Damage')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_damage']) ? number_format($datas['total_damage'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Expense')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['expense']) ? number_format($datas['expense'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Expense Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['expense_payment']) ? number_format($datas['expense_payment'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Asset Expense Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_asset_expense_payment']) ? number_format($datas['total_asset_expense_payment'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Monthly Expense Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_monthly_expense_payment']) ? number_format($datas['total_monthly_expense_payment'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Transport Expense Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_transport_expense_payment']) ? number_format($datas['total_transport_expense_payment'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Overall Expense Amount')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['overall_total_expense']) ? number_format($datas['overall_total_expense'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Salary Advance')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['salary_advance']) ? number_format($datas['salary_advance'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Employee Loan')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['employee_loan']) ? number_format($datas['employee_loan'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Salary Amount')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['salary_amount']) ? number_format($datas['salary_amount'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Salary Payment')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['salary_payment']) ? number_format($datas['salary_payment'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>



                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Over Time Allowance')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['over_time_allowance']) ? number_format($datas['over_time_allowance'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Office Loan')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['office_loan']) ? number_format($datas['office_loan'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Deposit')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['deposit']) ? number_format($datas['deposit'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Withdrawal')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['withdrawal']) ? number_format($datas['withdrawal'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Products Stock Value')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_physical_stock']) ? number_format($datas['total_physical_stock'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card mb-3 shadow">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Items Stock Value')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_item_physical_stock']) ? number_format($datas['total_item_physical_stock'], 2) : 0) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-0 text-capitalize">
                                @lang('Net Profit')

                            </h6>
                            <p>((@lang('Total Net Amount') - (@lang('Customer Commission') + @lang('Marketer Commission') + @lang('Total Expense') +
                                @lang('Product Damage') + @lang('Production Cost')) </p>
                            <p>
                                {{ $datas['total_net_amount'] }},
                                {{ $datas['total_customer_commission'] }},
                                {{ $datas['total_marketer_commission'] }},
                                {{ $datas['total_product_damage'] }},
                                {{ $datas['total_customer_product_damage'] }},
                                {{ $datas['total_expense_amount'] }},
                                {{ $datas['salary_amount'] }},
                                {{ $datas['total_datewish_total_sales_cost'] }} 
                            </p>

                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                @php
                                    $total_profit =
                                        $datas['total_net_amount'] -
                                        ($datas['total_customer_commission'] +
                                            $datas['total_marketer_commission'] +
                                            $datas['total_product_damage'] +
                                            $datas['total_customer_product_damage'] +
                                            $datas['total_expense_amount'] +
                                            $datas['salary_amount'] +
                                            $datas['total_datewish_total_sales_cost']);
                                @endphp

                                @if ($total_profit > 0)
                                    + {{ en2bn(number_format($total_profit, 2)) }}
                                @else
                                    - {{ en2bn(number_format(abs($total_profit), 2)) }}
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
