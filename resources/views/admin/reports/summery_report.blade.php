@extends('admin.layouts.app', ['title' => __('Summery')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Summery Report')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">

                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($datas['start_date'])) value="{{ $datas['start_date'] }}" @endif
                            class="form-control">
                    </div>

                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($datas['end_date'])) value="{{ $datas['end_date'] }}" @endif class="form-control">
                    </div>

                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary "><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary "><i class="bi bi-download"></i>
                            @lang('PDF')</button>

                    </div>

                </div>
            </form>


            <div class="row mt-4">
                <div class="col-12">
                    <p class=" mt-5">@lang('Date'): @if (isset($datas['start_date']))
                            {{ en2bn(Date('d-m-Y', strtotime($datas['start_date']))) }} -
                            {{ en2bn(Date('d-m-Y', strtotime($datas['end_date']))) }}
                        @endif
                    </p>

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    @include('admin.reports.includes.summery_report_table')
                </table>
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
                                @lang('Grand Total')
                            </h6>
                            <h6 class="mb-0 text-capitalize mt-2 text-success">
                                {{ en2bn(isset($datas['total_grand_total']) ? number_format($datas['total_grand_total'], 2) : 0) }}
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
                            <p>((@lang('Total Net Amount') - (@lang('Customer Commission') + @lang('Marketer Commission') + @lang('Product Damage')
                                + @lang('Customer Product Damage') + @lang('Total Expense') +
                                @lang('Salary Generate Amount') + @lang('Sales Cost')) </p>

                            {{ $datas['total_net_amount'] }},
                            {{ $datas['total_customer_commission'] }},
                            {{ $datas['total_marketer_commission'] }},
                            {{ $datas['total_product_damage'] }},
                            {{ $datas['total_customer_product_damage'] }},
                            {{ $datas['total_expense_amount'] }},
                            {{ $datas['salary_amount'] }},
                            {{ $datas['total_datewish_total_sales_cost'] }}

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
    </div>
@endsection
