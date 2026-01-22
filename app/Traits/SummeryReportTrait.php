<?php

namespace App\Traits;

use App\Models\User;
use App\Models\HR\Loan;
use App\Models\ItemOrder;
use App\Models\Order\Order;
use App\Models\MakeProduction;
use App\Models\Account\Deposit;
use App\Models\DailyProduction;
use App\Models\Expense\Expense;
use App\Models\Order\Quotation;
use App\Models\Product\Product;
use App\Models\HR\SalaryAdvance;
use App\Models\ItemOrderPayment;
use App\Models\HR\SalaryGenerate;
use App\Models\ItemReturnPayment;
use App\Models\Order\OrderReturn;
use App\Models\Account\Withdrawal;
use Illuminate\Support\Facades\DB;
use App\Models\HR\SalaryBonusSetup;
use App\Models\Account\CustomerLoan;
use App\Models\Account\OfficialLoan;
use App\Models\Account\OrderPayment;
use App\Models\HR\OverTimeAllowance;
use App\Models\Product\ProductStock;
use App\Models\Product\ProductDamage;
use App\Models\HR\SalaryPaymentHistory;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\SupplierDuePayment;
use App\Models\Account\CustomerLoanPayment;
use App\Models\Account\OfficialLoanPayment;
use App\Models\Expense\AssetExpensePayment;
use App\Models\Commission\CommissionInvoice;
use App\Models\Expense\ExpensePaymentHistory;
use App\Models\Expense\MonthlyExpensePayment;
use App\Models\Product\CustomerProductDamage;
use App\Models\Expense\TransportExpensePayment;
use App\Models\Commission\CommissionInvoicePayment;
use App\Models\Commission\MarketerCommissionPayment;


trait SummeryReportTrait
{

    protected function getOrders($start_date, $end_date)
    {
        $orders = Order::select(
            DB::raw('DATE(orders.date) as date'),
            DB::raw('SUM(orders.sub_total) as order_amount'),
            DB::raw('SUM(orders.net_amount) as order_net_amount'),
            DB::raw('SUM(orders.grand_total) as order_grand_total'),
            DB::raw('SUM(orders.commission) as customer_commission'),
            DB::raw('SUM(orders.marketer_commission) as marketer_commission'),
            DB::raw('SUM(orders.order_due) as main_order_due')
        )
            ->whereBetween('orders.date', [$start_date, $end_date])
            ->groupBy('orders.date')
            ->get();

        return $orders;
    }

    protected function unpaidCommissions($start_date, $end_date)
    {
        $orders = Order::select(
            DB::raw('DATE(orders.date) as date'),
            DB::raw('SUM(orders.commission) as unpaid_commission')
        )
            ->where('commission_status', 'Unpaid')
            ->whereBetween('orders.date', [$start_date, $end_date])
            ->groupBy('orders.date')
            ->get();

        return $orders;
    }

    protected function paidInvoiceCommissions($start_date, $end_date)
    {
        $payments = CommissionInvoicePayment::select(
            DB::raw('DATE(commission_invoice_payments.date) as date'),
            DB::raw('SUM(commission_invoice_payments.total_amount) as paid_commission')
        )
            ->whereBetween('commission_invoice_payments.date', [$start_date, $end_date])
            ->groupBy('commission_invoice_payments.date')
            ->get();

        return $payments;
    }

    protected function getQuotations($start_date, $end_date)
    {
        $quotations = Quotation::select(
            DB::raw('DATE(quotations.date) as date'),
            DB::raw('SUM(quotations.net_amount) as quotation_amount')
        )
            ->whereBetween('quotations.date', [$start_date, $end_date])
            ->groupBy('quotations.date')
            ->get();

        return $quotations;
    }

    protected function getOrderReturns($start_date, $end_date)
    {
        $orderReturns = OrderReturn::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(totalamount) as order_return_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $orderReturns;
    }

    protected function getOrderPayments($start_date, $end_date)
    {
        $orderPayments = OrderPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as paid_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $orderPayments;
    } 

    protected function getCustomerDuePayments($start_date, $end_date)
    {
        $customerDuePayments = CustomerDuePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as customer_due_payment')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $customerDuePayments;
    }


    protected function getCustomerOpeningDue($start_date, $end_date)
    {

        // check first invoice exist or not

        $checkexist = CommissionInvoice::where('month_id', Date('m', strtotime($end_date)) - 1)->where('year', Date('Y', strtotime($end_date)))->count();
        if ($checkexist) {
            $customerOpeningDue  =   CommissionInvoice::where('month_id', Date('m', strtotime($end_date)) - 1)->where('year', Date('Y', strtotime($end_date)))->sum('amount');
        } else {
            // $customerOpeningDue =  User::where('type', 'customer')->sum('last_month_due');
            $customerOpeningDue =  User::where('type', 'customer')->sum('opening');
        }

        return $customerOpeningDue;
    }


    protected function getProductDamage($start_date, $end_date)
    {
        $productDamages = ProductDamage::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(qty) as damage')
        )
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('created_at')
            ->get();

        return $productDamages;
    }

    protected function getCustomerProductDamage($start_date, $end_date)
    {
        $customerProductDamages = CustomerProductDamage::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as customer_damage_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $customerProductDamages;
    }

    public function getSupplierOpeningDue()
    {
        return User::where('type', 'supplier')->sum('opening');
    }

    protected function getItemOrders($start_date, $end_date)
    {
        $itemOrders = ItemOrder::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(totalamount) as item_order')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $itemOrders;
    }


    protected function getSupplierDuePayments($start_date, $end_date)
    {
        $supplierDuePayments = SupplierDuePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as supplier_due_payment')
        )->where('status', 'Paid')
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();


        return $supplierDuePayments;
    }

    protected function getItemOrderPayments($start_date, $end_date)
    {
        $itemOrderPayments = ItemOrderPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as item_order_payment')
        )
            ->where('status', 'Paid')
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $itemOrderPayments;
    }

    protected function getItemOrderReturnsPayments($start_date, $end_date)
    {
        $itemOrderReturns = ItemReturnPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as item_return_payment')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $itemOrderReturns;
    }


    protected function getExpenses($start_date, $end_date)
    {
        $expenses = Expense::select(
            DB::raw('DATE(expense_date) as date'),
            DB::raw('SUM(total_amount) as expense_amount')
        )->whereBetween('expense_date', [$start_date, $end_date])
            ->groupBy('expense_date')
            ->get();

        return $expenses;
    }

    protected function getExpensePayments($start_date, $end_date)
    {
        $expensesPayments = ExpensePaymentHistory::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as expense_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $expensesPayments;
    }

    protected function getMonthlyExpensePayments($start_date, $end_date)
    {
        $payments = MonthlyExpensePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as monthly_expense_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $payments;
    }

    protected function getAssetExpensePayments($start_date, $end_date)
    {
        $payments = AssetExpensePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as asset_expense_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $payments;
    }

    protected function getTransportExpensePayments($start_date, $end_date)
    {
        $payments = TransportExpensePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as transport_expense_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $payments;
    }

    protected function getSalaryAdvances($start_date, $end_date)
    {
        $salaries = SalaryAdvance::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as salary_advance_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->where('type', 'Regular')
            ->get();

        return $salaries;
    }

    protected function getSalaries($start_date, $end_date)
    {
        $salaries = SalaryGenerate::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(salary_amount) as amount')
        )
            //  ->whereBetween('date', [$start_date, $end_date])
            // ->where('month_id',2)
            ->where('month_id', Date('m', strtotime($start_date)))
            //->groupBy(DB::raw('DATE(date)'))
            ->get();

        return $salaries;
    }



    protected function getEmployeeLoans($start_date, $end_date)
    {
        $loans = Loan::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as employee_loan_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $loans;
    }

    protected function getSalaryPayments($start_date, $end_date)
    {
        $pay = SalaryPaymentHistory::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as salary_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $pay;
    }

    protected function getOverTimeAllowances($start_date, $end_date)
    {
        $allowance = OverTimeAllowance::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as allowance_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $allowance;
    }

    protected function getBonas($start_date, $end_date)
    {
        $bonas = SalaryBonusSetup::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as salary_bonas')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $bonas;
    }

    protected function getOfficeLoans($start_date, $end_date)
    {
        $officeLoans = OfficialLoan::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as office_loan')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $officeLoans;
    }

    protected function getOfficeLoanPayments($start_date, $end_date)
    {
        $officeLoanpayments = OfficialLoanPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as office_loan_payment')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $officeLoanpayments;
    }

    protected function getCustomerLoans($start_date, $end_date)
    {
        $customerLoans = CustomerLoan::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as customer_loan')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $customerLoans;
    }

    protected function getCustomerLoanPayments($start_date, $end_date)
    {
        $customerLoanPayments = CustomerLoanPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as customer_loan_payment')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $customerLoanPayments;
    }


    protected function getDeposits($start_date, $end_date)
    {
        $deposits = Deposit::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as deposit_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $deposits;
    }

    protected function getWithdrawals($start_date, $end_date)
    {
        $withdrawals = Withdrawal::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as withdrawl_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $withdrawals;
    }

    protected function getMarkerterCommssionPayments($start_date, $end_date)
    {
        $datas = MarketerCommissionPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as marketer_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $datas;
    }

    protected function getMakeProductions($start_date, $end_date)
    {
        $datas = MakeProduction::select(
            DB::raw('DATE(make_productions.date) as date'),
            DB::raw('SUM(make_productions.qty) as make_production_qty'),
            DB::raw('SUM(items.price * make_productions.qty) as item_price')
        )
            ->leftJoin('items', 'make_productions.item_id', '=', 'items.id')
            ->whereBetween('make_productions.date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $datas;
    }

    protected function getProductions($start_date, $end_date)
    {
        $datas = DailyProduction::select(
            DB::raw('DATE(daily_productions.date) as date'),
            DB::raw('SUM(daily_productions.qty) as daily_production_qty'),
            DB::raw('SUM(products.sale_price * daily_productions.qty) as product_price'),
            DB::raw('SUM(daily_productions.pp_cost) as production_pp_cost'),
            DB::raw('SUM(daily_productions.box_cost) as production_box_cost'),
            DB::raw('SUM(daily_productions.striker_cost) as production_striker_cost')
        )
            ->leftJoin('products', 'daily_productions.product_id', '=', 'products.id')
            ->whereBetween('daily_productions.date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        return $datas;
    }

    protected function getDailySalesOrderProductPrice($start_date, $end_date)
    {
        $costs = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('product_recipes', 'products.id', '=', 'product_recipes.product_id')
            ->leftJoin('items as pp_items', 'products.pp_item_id', '=', 'pp_items.id')
            ->leftJoin('items as box_items', 'products.box_item_id', '=', 'box_items.id')
            ->leftJoin('items as striker_items', 'products.striker_item_id', '=', 'striker_items.id')
            ->select(
                'orders.date',
                DB::raw('SUM(order_details.qty) as sale_qty'),
                DB::raw('SUM(product_recipes.cost_per_product * order_details.qty) as sale_cost'),
                DB::raw('sum(distinct((pp_items.price/1000)*products.pp_weight)) as pp_cost'),
                DB::raw('(box_items.price * order_details.qty) as box_cost'),
                DB::raw('(striker_items.price * order_details.qty) as striker_cost')
            )
            ->whereBetween('orders.date', [$start_date, $end_date])
            ->groupBy('orders.date')
            ->get();

        return $costs;
    }

    protected function getDailySalesOrderPBSCost($start_date, $end_date)
    {
        $costs = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('items as pp_items', 'products.pp_item_id', '=', 'pp_items.id')
            ->leftJoin('items as box_items', 'products.box_item_id', '=', 'box_items.id')
            ->leftJoin('items as striker_items', 'products.striker_item_id', '=', 'striker_items.id')
            ->select(
                'orders.date',
                DB::raw('SUM(order_details.qty) as sale_qty'),
                DB::raw('sum((pp_items.price/1000)*products.pp_weight) as pp_cost'),
                DB::raw('sum(box_items.price * order_details.qty) as box_cost'),
                DB::raw('sum(striker_items.price * order_details.qty) as striker_cost')
            )
            ->whereBetween('orders.date', [$start_date, $end_date])
            ->groupBy('orders.date')
            ->get();

        return $costs;
    }


    // Product Stocks Value

    protected function getProductStocksValue($start_date, $end_date)
    {
        $findmonth = date('m', strtotime($end_date));
        $year = date('Y', strtotime($end_date));

        // Check if there are any product stocks for the given month and year
        $count = ProductStock::where('month_id', $findmonth)->where('year', $year)->count();

        $amount = 0;

        if ($count > 0) {
            // Fetch product stocks for the given month and year
            $productstocks = ProductStock::where('month_id', $findmonth)->where('year', $year)->get();

            foreach ($productstocks as $stocks) {
                if ($stocks->product) { // Ensure product relationship exists
                    $amount += round($stocks->physical_stock * $stocks->product->sale_price, 2);
                }
            }
        } else {
            // Fetch active products and calculate opening stock value
            $products = Product::where('status', 'Active')->get();

            foreach ($products as $product) {
                $amount += round($product->opening_qty * $product->sale_price, 2);
            }
        }

        return $amount; // This is a float
    }
}
