<?php

namespace App\Http\Controllers\Admin\Report;

use PDF;
use App\Models\Item;
use App\Models\User;
use App\Models\HR\Loan;
use App\Models\ItemOrder;
use App\Models\ItemStock;
use App\Traits\PrintTrait;
use App\Models\HR\Employee;
use App\Models\Order\Order;
use App\Models\Report\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Account\Account;
use App\Models\Account\Deposit;
use App\Models\Expense\Expense;
use App\Models\Order\Quotation;
use App\Models\Product\Product;
use App\Models\HR\SalaryAdvance;
use App\Models\ItemOrderPayment;
use App\Models\HR\SalaryGenerate;
use App\Models\ItemReturnPayment;
use App\Models\Order\OrderReturn;
use App\Models\Report\Liabilitie;
use PhpParser\Node\Expr\FuncCall;
use App\Models\Account\ModuleType;
use App\Models\Account\Withdrawal;
use App\Models\Report\DailyReport;
use App\Traits\SummeryReportTrait;
use Illuminate\Support\Facades\DB;
use App\Exports\DailyArchiveExport;
use App\Models\Warehouse\Transport;
use App\Http\Controllers\Controller;
use App\Models\Account\CustomerLoan;
use App\Models\Account\OfficialLoan;
use App\Models\Account\OrderPayment;
use App\Models\HR\OverTimeAllowance;
use App\Models\Product\ProductStock;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product\ProductDamage;
use App\Models\HR\FestivalBonusPayment;
use App\Models\HR\SalaryPaymentHistory;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\SupplierDuePayment;
use App\Models\Account\TransactionHistory;
use App\Models\Account\CustomerLoanPayment;
use App\Models\Account\OfficialLoanPayment;
use App\Models\Expense\AssetExpensePayment;
use App\Models\Account\AccountCloseStatement;
use App\Models\Expense\ExpensePaymentHistory;
use App\Models\Expense\MonthlyExpensePayment;
use App\Models\Product\CustomerProductDamage;
use App\Models\Expense\TransportExpensePayment;
use App\Models\Commission\CommissionInvoicePayment;
use App\Models\Commission\MarketerCommissionPayment;

class ReportController extends Controller
{
    use PrintTrait, SummeryReportTrait;

    public function dailyreports(Request $request)
    {

        // Check if date is provided
        if ($request->date) {
            $data['date'] = $request->date;
            $data['searching']          = "Yes";
            $data['salesamount']        = Order::where('date', $request->date)->sum('net_amount');
            $data['salepayments']       = OrderPayment::where('date', $request->date)->sum('amount');
            $data['returnamounts']      = OrderReturn::where('date', $request->date)->sum('totalamount');
            $data['customerduepayment'] = CustomerDuePayment::where('date', $request->date)->sum('amount');
            $data['supplierduepaymnet'] = SupplierDuePayment::where('date', $request->date)->sum('amount');
            $data['loans']              = Loan::where('date', $request->date)->sum('total_amount');
            $data['officeloans']        = OfficialLoan::where('date', $request->date)->sum('total_amount');
            $data['officialloanpayment'] = OfficialLoanPayment::where('date', $request->date)->sum('amount');
            $data['deposit']            = Deposit::where('date', $request->date)->sum('amount');
            $data['itemorderamount']    = ItemOrder::where('date', $request->date)->sum('totalamount');
            $data['itempayments']       = ItemOrderPayment::where('date', $request->date)->sum('amount');
            $data['itemreturns']       = ItemOrder::where('date', $request->date)->sum('return_amount');
            $data['expense']            = Expense::where('expense_date', $request->date)->sum('total_amount');
            $data['expensepayments']    = ExpensePaymentHistory::where('date', $request->date)->sum('amount');
            $data['salaryadvance']      = SalaryAdvance::where('date', $request->date)->sum('amount');
            $data['salarypayment']      = SalaryPaymentHistory::where('date', $request->date)->sum('amount');
            $data['overtimeallowance']  = OverTimeAllowance::where('date', $request->date)->sum('amount');
            $data['withdrawal']         = Withdrawal::where('date', $request->date)->sum('amount');
            $data['assetexpensepayment']        = AssetExpensePayment::where('date', $request->date)->sum('amount');
            $data['monthlyexpensepayment']      = MonthlyExpensePayment::where('date', $request->date)->sum('amount');
            $data['transportexpensepayment']    = TransportExpensePayment::where('date', $request->date)->sum('amount');
            $data['marketercommissionpayment']  = MarketerCommissionPayment::where('date', $request->date)->sum('amount');
            $data['festivalbonuspayment']  = FestivalBonusPayment::where('date', $request->date)->sum('amount');

            // Account


            $data['cashaccount']   = Account::find(2);

            $PreviousDateBalance = DailyReport::where('date', '<', $request->date)
                ->orderBy('date', 'desc')
                ->first();

            if ($PreviousDateBalance) {
                $data['previousDateBalance'] =  $PreviousDateBalance->account_balance;
            } else {
                $data['previousDateBalance'] = $data['cashaccount']->opening_balance;
            }
        } else {
            $data['date'] = '';
            $data['searching'] = "No";
            $data['salesamount'] = 0;
            $data['salepayments'] = 0;
        }

        if ($request->has('search')) {
            return view('admin.reports.dailyreport', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.reports.dailyreport_pdf', $data);
            return $pdf->stream('daily_reports.pdf');
        } else {
            return view('admin.reports.dailyreport', $data);
        }
    }

    public function dailyarchive(Request $request)
    {
        $start_date = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::parse(date('Y-m-01'));

        $end_date = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::parse(date('Y-m-t'));

        $dates = [];

        for ($i = $start_date->copy(); $i <= $end_date; $i->addDay()) {
            $dates[] = $i->toDateString();
        }

        $datesCollection = collect($dates);

        $formattedDates = $datesCollection->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        });

        $quotations = Quotation::select(
            DB::raw('DATE(quotations.date) as date'),
            DB::raw('SUM(quotation_details.qty) as quotation_qty'),
            DB::raw('SUM(quotations.net_amount) as quotation_amount')
        )
            ->join('quotation_details', 'quotations.id', '=', 'quotation_details.quotation_id')
            ->whereBetween('quotations.date', [$start_date, $end_date])
            ->groupBy('quotations.date')
            ->get();


        $orders = Order::select(
            DB::raw('DATE(orders.date) as date'),
            DB::raw('SUM(order_details.qty) as order_qty'),
            DB::raw('SUM(orders.net_amount) as order_amount'),
            DB::raw('SUM(orders.customer_due) as order_due'),
            DB::raw('SUM(orders.commission) as commission_amount')
        )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereBetween('orders.date', [$start_date, $end_date])
            ->groupBy('orders.date')
            ->get();



        $orderPayments = OrderPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as paid_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        $customerDuePayments = CustomerDuePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as customer_due_payment')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();


        $orderReturns = OrderReturn::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(totalamount) as return_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();


        $archives = $formattedDates->map(function ($date) use ($quotations, $orders, $orderPayments, $customerDuePayments, $orderReturns) {

            $quotation = $quotations->firstWhere('date', $date);
            $order = $orders->firstWhere('date', $date);
            $orderPayment = $orderPayments->firstWhere('date', $date);
            $customerDuePayment = $customerDuePayments->firstWhere('date', $date);
            $orderReturn = $orderReturns->firstWhere('date', $date);

            return [
                'date' => $date,
                'quotation_qty' => $quotation->quotation_qty ?? 0,
                'quotation_amount' => $quotation->quotation_amount ?? 0,
                'order_qty' => $order->order_qty ?? 0,
                'order_amount' => $order->order_amount ?? 0,
                'paid_amount' => $orderPayment->paid_amount ?? 0,
                'order_due' => $order->order_due ?? 0,
                'customer_due_payment' => $customerDuePayment->customer_due_payment ?? 0,
                'commission_amount' => $order->commission_amount ?? 0,
                'order_return_amount' => $orderReturn->return_amount ?? 0,
            ];
        });



        $data = [
            'archives' => $archives,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'total_quotation_qty' => $archives->sum('quotation_qty'),
            'total_quotation_amount' => $archives->sum('quotation_amount'),
            'total_order_qty' => $archives->sum('order_qty'),
            'total_order_amount' => $archives->sum('order_amount'),
            'total_order_due' => $archives->sum('order_due'),
            'total_paid_amount' => $archives->sum('paid_amount'),
            'total_customer_due_payment' => $archives->sum('customer_due_payment'),
            'total_commission_amount' => $archives->sum('commission_amount'),
            'total_order_return_amount' => $archives->sum('order_return_amount'),
        ];


        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.reports.dailayarchive_export', ['datas' => $data]);
            return $pdf->stream('daily_reports.pdf');
        }

        if ($request->has('excel')) {
            return Excel::download(new DailyArchiveExport($data), 'daily_reports.xlsx');
        }


        return view('admin.reports.dailyarchive', ['datas' => $data]);
    }

    public function summery(Request $request)
    {
        $start_date = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::parse(date('Y-m-01'));

        $end_date = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::parse(date('Y-m-t'));

        $dates = [];

        for ($i = $start_date->copy(); $i <= $end_date; $i->addDay()) {
            $dates[] = $i->toDateString();
        }

        $datesCollection = collect($dates);

        $formattedDates = $datesCollection->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        });

        $orders = Order::select(
            DB::raw('DATE(orders.date) as date'),
            DB::raw('SUM(orders.net_amount) as sales'),
            DB::raw('SUM(orders.commission) as total_commission')
        )
            ->whereBetween('orders.date', [$start_date, $end_date])
            ->groupBy('orders.date')
            ->get();


        $orderReturns = OrderReturn::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(totalamount) as order_return')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();


        $orderPayments = OrderPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as paid_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        $customerDuePayments = CustomerDuePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as customer_due_payment')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();


        $productDamages = ProductDamage::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(qty) as damage')
        )
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('created_at')
            ->get();

        $customerProductDamages = CustomerProductDamage::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as customer_damage_amount')
        )
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        $purchaseProducts = ItemOrder::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(totalamount) as purchase_product')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();



        $supplierPayments =  ItemOrderPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as supp_payment')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        $supplierDuePayments =  SupplierDuePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as supplier_due_payment')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();



        $commissionPayments = CommissionInvoicePayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as commission_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();


        $officeLoans = OfficialLoan::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as office_loan')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();



        $customerLoans = CustomerLoan::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(total_amount) as customer_loan')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();    


        $customerLoansPayment = CustomerLoanPayment::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as customer_loan_payment')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();    


        $employeeAdvances = SalaryAdvance::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as salary_advance_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

        $expenses = Expense::select(
            DB::raw('DATE(expense_date) as date'),
            DB::raw('SUM(total_amount) as expense_amount')
        )->whereBetween('expense_date', [$start_date, $end_date])
            ->groupBy('expense_date')
            ->get();

        $expensesPayments = ExpensePaymentHistory::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as expense_payment_amount')
        )->whereBetween('date', [$start_date, $end_date])
            ->groupBy('date')
            ->get();

 

        $archives = $formattedDates->map(function ($date) use ($orders, $orderReturns, $orderPayments, $customerDuePayments, $productDamages, $purchaseProducts, $supplierPayments, $commissionPayments, $officeLoans, $customerLoans, $customerLoansPayment, $employeeAdvances, $expenses, $expensesPayments, $customerProductDamages, $supplierDuePayments) {


            $order = $orders->firstWhere('date', $date);
            $orderReturn = $orderReturns->firstWhere('date', $date);
            $orderPayment = $orderPayments->firstWhere('date', $date);
            $customerDuePayment = $customerDuePayments->firstWhere('date', $date);
            $productDamage = $productDamages->firstWhere('date', $date);
            $purchaseProduct = $purchaseProducts->firstWhere('date', $date);
            $supplierPayment = $supplierPayments->firstWhere('date', $date);
            $commissionPayment = $commissionPayments->firstWhere('date', $date);
            $officeLoan = $officeLoans->firstWhere('date', $date);
            $customerloan = $customerLoans->firstWhere('date', $date);
            $customerloanpay = $customerLoansPayment->firstWhere('date', $date);
            $employeeAdvance = $employeeAdvances->firstWhere('date', $date);
            $expense = $expenses->firstWhere('date', $date);
            $expensesPayment = $expensesPayments->firstWhere('date', $date);
            $customerProductDamage = $customerProductDamages->firstWhere('date', $date);
            $supplierDuePayment = $supplierDuePayments->firstWhere('date', $date);


            return [
                'date' => $date,
                'sales' => $order->sales ?? 0,
                'commission' => $order->total_commission ?? 0,
                'order_return' => $orderReturn->order_return ?? 0,
                'damage' => $productDamage->damage ?? 0,
                'customer_damage_amount' => $customerProductDamage->customer_damage_amount ?? 0,
                'purchase_product' => $purchaseProduct->purchase_product ?? 0,
                'supplier_payment' => $supplierPayment->supp_payment ?? 0,
                'customer_commission_due' => ($order->total_commission ?? 0) - ($commissionPayment->commission_payment_amount ?? 0),
                'supplier_due' => (($purchaseProduct->purchase_product ?? 0) - ($supplierPayment->supp_payment ?? 0)),
                'total_due' => ($order->sales ?? 0) - (($orderPayment->paid_amount ?? 0) + ($customerDuePayment->customer_due_payment ?? 0)),
                'office_loan' => $officeLoan->office_loan ?? 0,
                'customer_loan' => $customerloan->customer_loan ?? 0,
                'customer_loan_payment' => $customerloanpay->customer_loan_payment ?? 0,
                'employee_advance' => $employeeAdvance->salary_advance_amount ?? 0,
                'office_expense' => $expense->expense_amount ?? 0,
                'office_expense_payment' => $expensesPayment->expense_payment_amount ?? 0,
                'supplier_due_payment' => $supplierDuePayment->supplier_due_payment ?? 0
            ];
        });


        $data = [
            'archives' => $archives,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'total_sales' => $archives->sum('sales'),
            'total_commission' => $archives->sum('commission'),
            'total_order_return' => $archives->sum('order_return'),
            'total_damage' => $archives->sum('damage'),
            'total_customer_damage' => $archives->sum('customer_damage_amount'),
            'total_purchase' => $archives->sum('purchase_product'),
            'total_customer_commission_due' => $archives->sum('customer_commission_due'),
            'total_supplier_payment' => $archives->sum('supplier_payment'),
            'total_supplier_due' => $archives->sum('supplier_due'),
            'total_due_amount' => $archives->sum('total_due'),
            'total_office_loan' => $archives->sum('office_loan'),
            'total_customer_loan' => $archives->sum('customer_loan'),
            'total_customer_loan_payment' => $archives->sum('customer_loan_payment'),
            'total_employee_advance' => $archives->sum('employee_advance'),
            'total_office_expense' => $archives->sum('office_expense'),
            'total_office_expense_payment' => $archives->sum('office_expense_payment'),
            'total_supplier_due_payment' => $archives->sum('supplier_due_payment'),
        ];


        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.reports.summery_exports', ['datas' => $data]);
            return $pdf->stream('summery_exports.pdf');
        }

        return view('admin.reports.summery', ['datas' => $data]);
    }

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

    protected function geItemStocksValue($start_date, $end_date)
    {
        $findmonth = date('m', strtotime($end_date));
        $year = date('Y', strtotime($end_date));

        // Check if there are any product stocks for the given month and year
        $count = ItemStock::where('month_id', $findmonth)->where('year', $year)->count();

        $amount = 0;

        if ($count > 0) {
            // Fetch product stocks for the given month and year
            $itemstocks = ItemStock::where('month_id', $findmonth)->where('year', $year)->get();

            foreach ($itemstocks as $stocks) {
                if ($stocks->item) { // Ensure product relationship exists
                    $amount += round($stocks->physical_stock * $stocks->item->price, 2);
                }
            }
        } else {
            // Fetch active products and calculate opening stock value
            $items = Item::where('status', 'Active')->get();

            foreach ($items as $item) {
                $amount += round($item->opening_qty * $item->price, 2);
            }
        }

        return $amount; // This is a float
    }

    public function summeryReport(Request $request)
    {
        $start_date = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::parse(date('Y-m-01'));

        $end_date = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::parse(date('Y-m-t'));

        $dates = [];


        if(!$request->ajax()) {
            $data['start_date'] = $start_date->format('Y-m-d');
            $data['end_date'] = $end_date->format('Y-m-d');
            return view('admin.reports.summery_report', $data);
        }


        for ($i = $start_date->copy(); $i <= $end_date; $i->addDay()) {
            $dates[] = $i->toDateString();
        }

        $datesCollection = collect($dates);

        $formattedDates = $datesCollection->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        });
 
        $orders                 = $this->getOrders($start_date, $end_date)->keyBy('date');
        $quotations             = $this->getQuotations($start_date, $end_date)->keyBy('date');
        $orderReturns           = $this->getOrderReturns($start_date, $end_date)->keyBy('date');
        $orderPayments          = $this->getOrderPayments($start_date, $end_date)->keyBy('date');
        $customerOpeningDue     = $this->getCustomerOpeningDue($start_date, $end_date);
        $customerDuePayments    = $this->getCustomerDuePayments($start_date, $end_date)->keyBy('date');
        $productDamages         = $this->getProductDamage($start_date, $end_date)->keyBy('date');
        $customerProductDamages = $this->getCustomerProductDamage($start_date, $end_date)->keyBy('date');
        $getSupplierOpeningDue  = $this->getSupplierOpeningDue($start_date, $end_date);
        $getItemOrders          = $this->getItemOrders($start_date, $end_date)->keyBy('date');
        $getItemOrderPayments   = $this->getItemOrderPayments($start_date, $end_date)->keyBy('date');
        $getItemOrderReturnsPayments= $this->getItemOrderReturnsPayments($start_date, $end_date)->keyBy('date');
        $unpaidCommissions      = $this->unpaidCommissions($start_date, $end_date)->keyBy('date');
        $supplierDuePayments    = $this->getSupplierDuePayments($start_date, $end_date)->keyBy('date');
        $expenses               = $this->getExpenses($start_date, $end_date)->keyBy('date');
        $expensePayments        = $this->getExpensePayments($start_date, $end_date)->keyBy('date');
        $monthlyExpensePayments = $this->getMonthlyExpensePayments($start_date, $end_date)->keyBy('date');
        $assetExpensePayments   = $this->getAssetExpensePayments($start_date, $end_date)->keyBy('date');
        $transportExpensePayments= $this->getTransportExpensePayments($start_date, $end_date)->keyBy('date');
        $getSalaryAdvances      = $this->getSalaryAdvances($start_date, $end_date)->keyBy('date');
        $getSalaries            = $this->getSalaries($start_date, $end_date)->keyBy('date');
        $getEmployeeLoans       = $this->getEmployeeLoans($start_date, $end_date)->keyBy('date');
        $getSalaryPayments      = $this->getSalaryPayments($start_date, $end_date)->keyBy('date');
        $getOverTimeAllowances  = $this->getOverTimeAllowances($start_date, $end_date)->keyBy('date');

        $getBonas               = $this->getBonas($start_date, $end_date)->keyBy('date');

        $getOfficeLoans         = $this->getOfficeLoans($start_date, $end_date)->keyBy('date');
        $getOfficeLoanPayments  = $this->getOfficeLoanPayments($start_date, $end_date)->keyBy('date');

        $getCustomerLoans       = $this->getCustomerLoans($start_date, $end_date)->keyBy('date');
        $getCustomerLoanPayments= $this->getCustomerLoanPayments($start_date, $end_date)->keyBy('date');

        $getDeposits            = $this->getDeposits($start_date, $end_date)->keyBy('date');
        $getWithdrawals         = $this->getWithdrawals($start_date, $end_date)->keyBy('date');
        $getMarkerterCommssionPayments = $this->getMarkerterCommssionPayments($start_date, $end_date)->keyBy('date');

        $getMakeProductions = $this->getMakeProductions($start_date, $end_date)->keyBy('date');
        $getProductions = $this->getProductions($start_date, $end_date)->keyBy('date');

        $costs      = $this->getDailySalesOrderProductPrice($start_date, $end_date)->keyBy('date');
        $pbscosts   = $this->getDailySalesOrderPBSCost($start_date, $end_date)->keyBy('date');
        // return $getSalaries;

       

        $archives = $formattedDates->map(function ($date) use (
            $quotations,            
            $orders,
            $orderReturns,
            $orderPayments,
            $customerDuePayments,
            $productDamages,
            $customerProductDamages,
            $getItemOrders,
            $getItemOrderPayments,
            $getItemOrderReturnsPayments,
            $supplierDuePayments,
            $unpaidCommissions,
            $expenses,
            $expensePayments,
            $monthlyExpensePayments,
            $assetExpensePayments,
            $transportExpensePayments,
            $getSalaryAdvances,
            $getSalaries,
            $getEmployeeLoans,
            $getSalaryPayments,
            $getOverTimeAllowances,
            $getBonas,
            $getOfficeLoans,
            $getOfficeLoanPayments,
            $getCustomerLoans,
            $getCustomerLoanPayments,
            $getDeposits,
            $getWithdrawals,
            $getMarkerterCommssionPayments,
            $getMakeProductions,
            $getProductions,
            $costs,
            $pbscosts
        ) {

            $quotation = $quotations[$date] ?? null;           
            $order = $orders[$date] ?? null;
            $orderReturn = $orderReturns[$date] ?? null;
            $orderPayment = $orderPayments[$date] ?? null;
            $customerDuePayment = $customerDuePayments[$date] ?? null;
            $productDamage = $productDamages[$date] ?? null;
            $customerProductDamage = $customerProductDamages[$date] ?? null;
            $customerProductDamage = $customerProductDamages[$date] ?? null;
            $getItemOrder = $getItemOrders[$date] ?? null;
            $getItemOrderPayment = $getItemOrderPayments[$date] ?? null;
            $getItemOrderReturnsPayment = $getItemOrderReturnsPayments[$date] ?? null;
            $supplierDuePayment = $supplierDuePayments[$date] ?? null;
            $unpaidCommission = $unpaidCommissions[$date] ?? null;
            $expense = $expenses[$date] ?? null;
            $expensePayment = $expensePayments[$date] ?? null;
            $monthlyExpensePayment = $monthlyExpensePayments[$date] ?? null;
            $assetExpensePayment = $assetExpensePayments[$date] ?? null;
            $transportExpensePayment = $transportExpensePayments[$date] ?? null;
            $getSalaryAdvance = $getSalaryAdvances[$date] ?? null;
            $getSalary                      = $getSalaries[$date] ?? null;
            $getEmployeeLoan                = $getEmployeeLoans[$date] ?? null;;
            $getSalaryPayment               = $getSalaryPayments[$date] ?? null;
            $getOverTimeAllowance           = $getOverTimeAllowances[$date] ?? null;
            $getBonas                       = $getBonas[$date] ?? null;
            $getOfficeLoan                  = $getOfficeLoans[$date] ?? null;
            $getOfficeLoanPayment           = $getOfficeLoanPayments[$date] ?? null;
            $getCustomerLoan                = $getCustomerLoans[$date] ?? null;
            $getCustomerLoanPayment         = $getCustomerLoanPayments[$date] ?? null;
            $getDeposit                     = $getDeposits[$date] ?? null;
            $getWithdrawal                  = $getWithdrawals[$date] ?? null;
            $getMarkerterCommssionPayment   = $getMarkerterCommssionPayments[$date] ?? null;

            $getMakeProduction              = $getMakeProductions[$date] ?? null;
            $getProduction                  = $getProductions[$date] ?? null;
            $cost                           = $costs[$date] ?? null;
            $pbscost                        = $pbscosts[$date] ?? null;


            return [
                'date' => $date,
                'quotation_order_amount'    => $quotation->quotation_amount ?? 0,                
                'order_amount'              => $order->order_amount ?? 0,
                'order_return_amount'       => $orderReturn->order_return_amount ?? 0,
                'net_amount'                => $order->order_net_amount ?? 0,
                'grand_total'               => $order->order_grand_total ?? 0,
                'customer_commission'       => $order->customer_commission ?? 0,
                'paid_amount'               => $orderPayment->paid_amount ?? 0,
                'main_order_due_amount'     => $order->main_order_due ?? 0,
                'customer_due_payment'      => $customerDuePayment->customer_due_payment ?? 0,
                'marketer_commission'       => $order->marketer_commission ?? 0,
                'product_damage'            => $productDamage->damage ?? 0,
                'customer_product_damage'   => $customerProductDamage->customer_damage_amount ?? 0,
                'item_order'                => $getItemOrder->item_order ?? 0,
                'item_order_payment'        => $getItemOrderPayment->item_order_payment ?? 0,
                'item_order_return_payment' => $getItemOrderReturnsPayment->item_return_payment ?? 0,
                'supplier_due_payment'      => $supplierDuePayment->supplier_due_payment ?? 0,
                'unpaid_commission'         => $unpaidCommission->unpaid_commission ?? 0,
                'expense'                   => $expense->expense_amount ?? 0,
                'expense_payment'           => $expensePayment->expense_payment_amount ?? 0,
                'monthly_expense_payment'   => $monthlyExpensePayment->monthly_expense_payment_amount ?? 0,
                'asset_expense_payment'     => $assetExpensePayment->asset_expense_payment_amount ?? 0,
                'transport_expense_payment' => $transportExpensePayment->transport_expense_payment_amount ?? 0,
                'salary_advance_amount'     => $getSalaryAdvance->salary_advance_amount ?? 0,
                'employee_amount' => $getEmployeeLoan->employee_loan_amount ?? 0,
                'salary' => $getSalary->amount ?? 0,
                'salary_payment_amount' => $getSalaryPayment->salary_payment_amount ?? 0,
                'over_time_allowance_amount' => $getOverTimeAllowance->allowance_amount ?? 0,
                'bonus' => $getBonas->salary_bonas ?? 0,
                'office_loan' => $getOfficeLoan->office_loan ?? 0,

                'customer_loan' => $getCustomerLoan->customer_loan ?? 0,
                'customer_loan_payment' => $getCustomerLoanPayment->customer_loan_payment ?? 0,

                'deposit' => $getDeposit->deposit_amount ?? 0,
                'withdrawal' => $getWithdrawal->withdrawl_amount ?? 0,
                'marketer_commission_payment' => $getMarkerterCommssionPayment->marketer_amount ?? 0,


                // sales items cost
                'sales_cost'                => $cost->sale_cost ?? 0,
                'pp_cost'                   => $pbscost->pp_cost ?? 0,
                'box_cost'                  => $pbscost->box_cost ?? 0,
                'striker_cost'              => $pbscost->striker_cost ?? 0,
                'datewish_total_sales_cost' => ($cost->sale_cost ?? 0) + ($pbscost->pp_cost ?? 0) + ($pbscost->box_cost ?? 0) + ($pbscost->striker_cost ?? 0),
                'sales_profit'              => ($order->order_net_amount ?? 0) - (($cost->sale_cost ?? 0) + ($pbscost->pp_cost ?? 0) + ($pbscost->box_cost ?? 0) + ($pbscost->striker_cost ?? 0)),
                // sales items cost end

                'make_production_qty'   => $getMakeProduction->make_production_qty ?? 0,
                'make_production_cost'  => $getMakeProduction->item_price ?? 0,

                'daily_production_pp_cost' => $getProduction->production_pp_cost ?? 0,
                'daily_production_box_cost' => $getProduction->production_box_cost ?? 0,
                'daily_production_striker_cost' => $getProduction->production_striker_cost ?? 0,

                'daily_make_production_cost'    => ($getMakeProduction->item_price ?? 0) + ($getProduction->production_pp_cost ?? 0) + ($getProduction->production_box_cost ?? 0) + ($getProduction->production_striker_cost ?? 0),

                'daily_production_qty' => $getProduction->daily_production_qty ?? 0,
                'daily_production_cost' => $getProduction->product_price ?? 0,
                'daily_production_profit' => ($getProduction->product_price ?? 0) - ($getMakeProduction->item_price ?? 0) - ($getProduction->production_pp_cost ?? 0) - ($getProduction->production_box_cost ?? 0) - ($getProduction->production_striker_cost ?? 0),
            ];
        });

        $itemsstockamount  = 0;
        $currentitemsstockamount  = 0;

        foreach (Item::get() as $item) {
            $itemsstockamount           += $item->price * floatval($item->opening_qty);
            $currentitemsstockamount    += $item->price * $item->stock($item->id);
        }


        $data = [
            'archives' => $archives,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'total_physical_stock'          => $this->getProductStocksValue($start_date,$end_date),
            'total_item_physical_stock'     => $this->geItemStocksValue($start_date,$end_date),
            'total_quotation_order_amount'  => $archives->sum('quotation_order_amount'),             
            'total_order_amount'            => $archives->sum('order_amount'),
            'total_order_return_amount'     => $archives->sum('order_return_amount'),
            'total_net_amount'              => $archives->sum('net_amount'),
            'total_grand_total'             => $archives->sum('net_amount') -  $archives->sum('customer_commission'),
            'total_customer_commission'     => $archives->sum('customer_commission'),
            'total_paid_amount'             => $archives->sum('paid_amount'),
            'total_customer_due_payment'    => $archives->sum('customer_due_payment'),
            'total_marketer_commission'     => $archives->sum('marketer_commission'),
            'total_main_order_due_amount'   => (($archives->sum('net_amount')) - ($archives->sum('customer_commission') + $archives->sum('paid_amount') + $archives->sum('customer_due_payment'))),
            'total_order_due'               => (($customerOpeningDue + $archives->sum('net_amount')) - ($archives->sum('customer_commission') + $archives->sum('paid_amount')+$archives->sum('customer_due_payment'))),
            'total_product_damage'          => $archives->sum('product_damage'),
            'total_customer_product_damage' => $archives->sum('customer_product_damage'),
            'total_item_order'              => $archives->sum('item_order'),
            'total_item_order_payments'     => $archives->sum('item_order_payment'),
            'total_supplier_due_payment'    => $archives->sum('supplier_due_payment'),
            'total_supplier_due_amount'     => ($archives->sum('item_order') - ($archives->sum('item_order_payment') + $archives->sum('item_order_return_payment') + $archives->sum('supplier_due_payment'))),
            'total_supplier_payable_amount' => ($getSupplierOpeningDue + $archives->sum('item_order')) - ($archives->sum('item_order_payment') + $archives->sum('item_order_return_payment') + $archives->sum('supplier_due_payment')),
            'total_damage' => $archives->sum('product_damage') + $archives->sum('customer_product_damage'),
            'payable_commission' => $archives->sum('unpaid_commission'),

            'expense'                           => $archives->sum('expense'),
            'expense_payment'                   => $archives->sum('expense_payment'),
            'total_asset_expense_payment'       => $archives->sum('asset_expense_payment'),
            'total_monthly_expense_payment'     => $archives->sum('monthly_expense_payment'),
            'total_transport_expense_payment'   => $archives->sum('transport_expense_payment'),
            'total_expense_amount'              => $archives->sum('expense_payment') + $archives->sum('monthly_expense_payment') + $archives->sum('transport_expense_payment'),
            'overall_total_expense'             => $archives->sum('expense_payment') + $archives->sum('asset_expense_payment') + $archives->sum('monthly_expense_payment') + $archives->sum('transport_expense_payment'),

            'salary_advance' => $archives->sum('salary_advance_amount'),
            'employee_loan' => $archives->sum('employee_loan_amount'),
            'salary_amount' => Employee::where('status','Active')->sum('Salary'),

            'salary_payment' => $archives->sum('salary_payment_amount'),

            'over_time_allowance'   => $archives->sum('over_time_allowance_amount'),
            'bonus'                 => $archives->sum('bonus'),
            'office_loan'           => $archives->sum('office_loan'),
            'office_loan_payment'   => $archives->sum('office_loan_payment'),
            'customer_loan'         => $archives->sum('customer_loan'),
            'customer_loan_payment' => $archives->sum('customer_loan_payment'),
            'deposit'               => $archives->sum('deposit'),
            'withdrawal'            => $archives->sum('withdrawal'),
            'marketer_commission_payment' => $archives->sum('marketer_commission_payment'),


            'total_sales_cost'                  => $archives->sum('sales_cost'),
            'total_pp_cost'                     => $archives->sum('pp_cost'),
            'total_box_cost'                    => $archives->sum('box_cost'),
            'total_striker_cost'                => $archives->sum('striker_cost'),
            'total_datewish_total_sales_cost'   => $archives->sum('datewish_total_sales_cost'),
            'total_sales_profit'                => $archives->sum('sales_profit'),


            'total_make_production_qty' => $archives->sum('make_production_qty'),
            'total_make_production_cost' => $archives->sum('make_production_cost'),

            'total_daily_production_pp_cost'        => $archives->sum('daily_production_pp_cost'),
            'total_daily_production_box_cost'       => $archives->sum('daily_production_box_cost'),
            'total_daily_production_striker_cost'   => $archives->sum('daily_production_striker_cost'),

            'total_production_cost'                 => $archives->sum('make_production_cost') + $archives->sum('daily_production_pp_cost') + $archives->sum('daily_production_box_cost') + $archives->sum('daily_production_striker_cost'),
            'total_daily_production_qty'    => $archives->sum('daily_production_qty'),
            'total_daily_production_cost'   => $archives->sum('daily_production_cost'),
            'total_production_profit'       => $archives->sum('daily_production_cost') - ($archives->sum('make_production_cost') + $archives->sum('daily_production_pp_cost') + $archives->sum('daily_production_box_cost') + $archives->sum('daily_production_striker_cost')),
            'opening_itemstockvalue'        => $itemsstockamount,
            'current_itemstockvalue'        => $currentitemsstockamount,
            'overall_production_cost'       => ($itemsstockamount + $currentitemsstockamount) - $archives->sum('make_production_cost')

        ];


        if ($request->has('pdf')) {
             $pdf = PDF::loadView('admin.reports.summery_exports', ['datas' => $data], [], [
                'format' => 'A4', // or 'A3', 'Letter', etc.
                'orientation' => 'L' // 'P' for Portrait, 'L' for Landscape
            ]);
            return $pdf->stream('summery_exports.pdf');
        }


        if ($request->ajax()) {
            return view('admin.reports.summery_report_data', [
                'datas' => $data
            ])->render();
        }

        return view('admin.reports.summery_report');
    }

    // trail Balance
    public function trialbalance(Request $request)
    {
        // Check if date is provided
        if ($request->start_date && $request->end_date) {

            $data['start_date']     = $request->start_date;
            $data['end_date']       = $request->end_date;
            $data['searching']      = "Yes";
            
            
            $monthclosedcount = DailyReport::count();
            
            if($monthclosedcount)
            {
                $data['availablebalance'] = DailyReport::orderBy('date','desc')->first()->account_balance;
            }
            else{
                $data['availablebalance'] = Account::find(2)->opening_balance;
            }
            
            

            $data['opendingcustomerdue']    = User::where('type', 'customer')->sum('opening');
            $data['opendingsupplierdue']    = User::where('type', 'supplier')->sum('opening');

            // Orders
            $data['totalsales']             = Order::whereBetween('date', [$request->start_date, $request->end_date])->count();
            $data['salesamount']            = Order::whereBetween('date', [$request->start_date, $request->end_date])->sum('net_amount');
            $data['ordercommissionamount']  = Order::whereBetween('date', [$request->start_date, $request->end_date])->sum('commission');
            $data['unpaidcommissionamount'] = Order::whereBetween('date', [$request->start_date, $request->end_date])->where('commission_status', 'Unpaid')->sum('commission');
            $data['salepayments']           = OrderPayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['returnamounts']          = OrderReturn::whereBetween('date', [$request->start_date, $request->end_date])->sum('totalamount');
            $data['customerduepayment']     = CustomerDuePayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['commissioninvoicepayment']   = CommissionInvoicePayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['marketercommissionpayment']  = MarketerCommissionPayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');


            // Receivable Amount
            $data['totalreceivableamount'] = ($data['opendingcustomerdue'] +  $data['salesamount'] + $data['unpaidcommissionamount']) - ($data['salepayments'] + $data['customerduepayment']) - ($data['unpaidcommissionamount'] +  $data['marketercommissionpayment']);


            // Items
            $data['totalitemorder']     = ItemOrder::whereBetween('date', [$request->start_date, $request->end_date])->count();
            $data['itemorderamount']    = ItemOrder::whereBetween('date', [$request->start_date, $request->end_date])->sum('totalamount');
            $data['itempayments']       = ItemOrderPayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['itemreturns']        = ItemOrder::whereBetween('date', [$request->start_date, $request->end_date])->sum('return_amount');
            $data['supplierduepaymnet'] = SupplierDuePayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');


            // HR Expense
            $data['totalsalary']        = SalaryGenerate::sum('salary_amount');
            $data['totalloansalary']    = SalaryGenerate::sum('loan_amount');
            $data['totaladvancesalary'] = SalaryGenerate::sum('advance_salary_amount');
            $data['payable_amount'] = SalaryGenerate::sum('payable_amount');

            $data['salarypayment']      = SalaryPaymentHistory::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');

            $data['payablesalary']      = $data['payable_amount'] - $data['salarypayment'];
            $data['salaryadvance']      = SalaryAdvance::whereBetween('date',[$request->start_date, $request->end_date])->where('type','Regular')->sum('amount');
            
           

            $data['overtimeallowance']  = OverTimeAllowance::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['loans']              = Loan::whereBetween('date', [$request->start_date, $request->end_date])->where('type','Regular')->sum('total_amount');
            $data['total_loan_paid']    = SalaryGenerate::sum('loan_amount');
            $data['total_loan_due']     = $data['loans'] - $data['total_loan_paid'];



            // Expense
            $data['expense']            = Expense::whereBetween('expense_date', [$request->start_date, $request->end_date])->sum('total_amount');
            $data['expensepayments']    = ExpensePaymentHistory::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['assetexpensepayment']        = AssetExpensePayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['monthlyexpensepayment']      = MonthlyExpensePayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['transportexpensepayment']    = TransportExpensePayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');

            // Official & Administration
            $data['officeloans']        = OfficialLoan::whereBetween('date', [$request->start_date, $request->end_date])->sum('total_amount');
            $data['officialloanpayment'] = OfficialLoanPayment::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['payableofficeloan']  =  $data['officeloans'] -   $data['officialloanpayment'];
            $data['deposit']            = Deposit::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');
            $data['withdrawal']         = Withdrawal::whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');




            // Payable Amount
            $data['totalpayableamount'] = ($data['opendingsupplierdue'] + $data['itemorderamount']) - ($data['itempayments'] + $data['supplierduepaymnet']);
            
            
            // Income
            
            $data['totalincome'] = $data['salepayments'] +  $data['customerduepayment'] +  $data['deposit'] +  $data['officeloans']  ; 
            
            // Expense
            
            $data['totalexpenditure'] =   $data['itempayments'] +   $data['supplierduepaymnet'] 
                                        + $data['expensepayments'] +  $data['assetexpensepayment'] + $data['monthlyexpensepayment'] + $data['transportexpensepayment'] 
                                        + $data['salarypayment']  +  $data['salaryadvance'] + $data['loans'] +  $data['overtimeallowance']  
                                        +  $data['withdrawal'] +  $data['marketercommissionpayment'] + $data['officialloanpayment'];
            


            // total product amount and total stock amount
            $productsstockamount    = 0;
            $itemsstockamount       = 0;

            foreach (Product::get() as $product) {
                $productsstockamount += $product->sale_price * $product->getstock($product->id);
            }

            $data['productstockvalue'] = $productsstockamount;

            foreach (Item::get() as $item) {
                $itemsstockamount += $item->price * $item->stock($item->id);
            }
            $data['itemstockvalue'] = $itemsstockamount;


            $data['assets'] = Asset::sum('price');


            // Account
             
            $data['cashaccount']   = Account::find(2);
            
        } else {
            $data['date'] = '';
            $data['searching'] = "No";
            $data['salesamount'] = 0;
            $data['salepayments'] = 0;
        }

        if ($request->has('search')) {
            return view('admin.reports.trialbalance', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.reports.trialbalance_export', $data);
            return $pdf->stream('trial_balance.pdf');
        } else {
            return view('admin.reports.trialbalance', $data);
        }
    }
    
    
    // cash register   
    public function cashregister(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return view('admin.reports.cashregister', ['searching' => 'No']);
        }

        $data['start_date'] = $request->start_date;
        $data['end_date']   = $request->end_date;
        $data['searching']  = "Yes";

        $lastmonthbalance = AccountCloseStatement::where('month_id', 11)->first()->amount;
        $runningBalance = $lastmonthbalance;
        /*
        |--------------------------------------------------------------------------
        | STEP 1: COLLECT ALL DATES FROM ALL MODULES
        |--------------------------------------------------------------------------
        */
        $dates = collect()

            ->merge(OrderPayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(CustomerDuePayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(ItemOrderPayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(ExpensePaymentHistory::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(SalaryPaymentHistory::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(Deposit::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(Withdrawal::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(OfficialLoanPayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(MarketerCommissionPayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(OverTimeAllowance::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(AssetExpensePayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(MonthlyExpensePayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(TransportExpensePayment::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))
            ->merge(Loan::whereBetween('date', [$request->start_date, $request->end_date])->pluck('date'))

            ->map(fn($d) => date('Y-m-d', strtotime($d)))
            ->unique()
            ->sort()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | STEP 2: BUILD DATE WISE CASH REGISTER
        |--------------------------------------------------------------------------
        */
        $cashRegisters = [];

        foreach ($dates as $date) {

            // ---------- INCOME ----------
            $sales_payment    = OrderPayment::whereDate('date', $date)->sum('amount');
            $customer_due     = CustomerDuePayment::whereDate('date', $date)->sum('amount');
            $deposit          = Deposit::whereDate('date', $date)->sum('amount');
            $office_loan      = OfficialLoan::whereDate('date', $date)->sum('total_amount');

            $totalIncome = $sales_payment + $customer_due + $deposit + $office_loan;

            // ---------- EXPENSE ----------
            $item_payment      = ItemOrderPayment::whereDate('date', $date)->sum('amount');
            $supplier_payment  = SupplierDuePayment::whereDate('date', $date)->sum('amount');

            $expense_payment   = ExpensePaymentHistory::whereDate('date', $date)->sum('amount');
            $asset_expense     = AssetExpensePayment::whereDate('date', $date)->sum('amount');
            $monthly_expense   = MonthlyExpensePayment::whereDate('date', $date)->sum('amount');
            $transport_expense = TransportExpensePayment::whereDate('date', $date)->sum('amount');

            $salary_payment    = SalaryPaymentHistory::whereDate('date', $date)->sum('amount');
            $salary_advance    = SalaryAdvance::whereDate('date', $date)->sum('amount');
            $loan              = Loan::whereDate('date', $date)->sum('amount');
            $overtime          = OverTimeAllowance::whereDate('date', $date)->sum('amount');

            $withdrawal        = Withdrawal::whereDate('date', $date)->sum('amount');
            $office_loan_pay   = OfficialLoanPayment::whereDate('date', $date)->sum('amount');
            $marketer_comm     = MarketerCommissionPayment::whereDate('date', $date)->sum('amount');

            $totalExpense =
                $item_payment + $supplier_payment +
                $expense_payment + $asset_expense + $monthly_expense + $transport_expense +
                $salary_payment + $salary_advance + $loan + $overtime +
                $withdrawal + $office_loan_pay + $marketer_comm;

            // ---------- RUNNING BALANCE ----------
            $runningBalance = $runningBalance + $totalIncome - $totalExpense;

            $cashRegisters[] = [
                'date' => $date,

                // income
                'sales_payment' => $sales_payment,
                'customer_due'  => $customer_due,
                'deposit'       => $deposit,
                'office_loan'   => $office_loan,

                // expense
                'item_payment'     => $item_payment,
                'supplier_payment' => $supplier_payment,

                'expense_payment'          => $expense_payment,
                'asset_expensepayment'     => $asset_expense,
                'monthly_expensepayment'   => $monthly_expense,
                'transport_expensepayment' => $transport_expense,

                'salary_payment'     => $salary_payment,
                'salary_advance'     => $salary_advance,
                'loan'               => $loan,
                'overtime_allowance' => $overtime,

                'withdrawal'                  => $withdrawal,
                'office_loan_payment'         => $office_loan_pay,
                'marketer_commission_payment' => $marketer_comm,

                // balance
                'balance' => $runningBalance,
            ];
        }

        $data['cashRegisters'] = $cashRegisters;
        $data['opening_balance'] = $lastmonthbalance;
        $data['closing_balance'] = $runningBalance;

        /*
        |--------------------------------------------------------------------------
        | COLUMN WISE TOTAL (FOR TFOOT)
        |--------------------------------------------------------------------------
        */
        $data['total_sales_payment']     = collect($cashRegisters)->sum('sales_payment');
        $data['total_customer_due']      = collect($cashRegisters)->sum('customer_due');
        $data['total_deposit']           = collect($cashRegisters)->sum('deposit');
        $data['total_office_loan']       = collect($cashRegisters)->sum('office_loan');


        $data['total_item_payment']      = collect($cashRegisters)->sum('item_payment');
        $data['total_supplier_payment']         = collect($cashRegisters)->sum('supplier_payment');

        $data['total_expense_payment']          = collect($cashRegisters)->sum('expense_payment');
        $data['total_asset_expensepayment']      = collect($cashRegisters)->sum('asset_expensepayment');
        $data['total_monthly_expensepayment']    = collect($cashRegisters)->sum('monthly_expensepayment');
        $data['total_transport_expensepayment']  = collect($cashRegisters)->sum('transport_expensepayment');

        $data['total_salary_payment']           = collect($cashRegisters)->sum('salary_payment');
        $data['total_salary_advance']           = collect($cashRegisters)->sum('salary_advance');
        $data['total_loan']                     = collect($cashRegisters)->sum('loan');
        $data['total_overtime_allowance']        = collect($cashRegisters)->sum('overtime_allowance');

        $data['total_withdrawal']               = collect($cashRegisters)->sum('withdrawal');
        $data['total_office_loan_payment']      = collect($cashRegisters)->sum('office_loan_payment');
        $data['total_marketer_commission_payment']       = collect($cashRegisters)->sum('marketer_commission_payment');




        if ($request->has('search')) {
            return view('admin.reports.cashregister', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.reports.cashregister_export', $data, [], [
                'format' => 'a4',
                'orientation' => 'landscape'
            ]);
            return $pdf->stream('cashregister.pdf');
        } else {
            return view('admin.reports.cashregister', $data);
        }
    }


    // Balance Sheet

    public function balancesheets(Request $request)
    {


        $data['assetsamount']   = Asset::sum('price');
        $data['liabilities']    = Liabilitie::sum('amount');
        $data['assetexpenses']  = AssetExpensePayment::sum('amount');
        $data['employeesalary'] = Employee::where('status','Active')->sum('salary');
        $data['monthlyexpense'] = MonthlyExpensePayment::sum('amount');
        $data['employeeloan']   = Loan::sum('amount') - SalaryGenerate::sum('loan_amount');
        
        $data['salaryadvances'] = SalaryAdvance::where('type','Regular')->where('month_id',1)->sum('amount');

        $receivable = 0;
        $customers  = User::where('type', 'customer')->get();
        foreach ($customers as $customer) {
            $receivable += $customer->receivable($customer->id);
        }
        $data['receivables'] = $receivable;

        $payable = 0;
        $suppliers = User::where('type', 'supplier')->get();
        foreach ($suppliers as $supplier) {
            $payable += $supplier->payable($supplier->id);
        }
        $data['payables'] = $payable;

        $data['salarypayable']      = $data['employeesalary'];


        $itemstockvalue = 0;
        $items = Item::where('status', 'Active')->get();

        foreach ($items as $item) {
            $itemstockvalue += $item->stock($item->id) * $item->price;
        }

        $data['itemstockamount']    = $itemstockvalue;


        $prductstockvalue = 0;
        $products = Product::where('status', 'Active')->get();

        foreach ($products as $product) {
            $prductstockvalue += $product->getstock($product->id) * $product->sale_price;
        }

        $data['prductstockamount']    = $prductstockvalue;
        
            
        $data['jamanot']            = 73400;
        $data['factoryrent']        = 50000;
        

        // Overall
        $data['totalassets']        =  $data['assetsamount']  +  $data['assetexpenses'] + $data['salaryadvances'] +  $data['prductstockamount'] + $data['itemstockamount'] + $data['employeeloan'];
        $data['totalliabilities']   =  $data['liabilities'] +  $data['employeesalary'] +  $data['payables'] +  $data['monthlyexpense'] + $data['jamanot'] + $data['factoryrent'];
        
        $data['differentvalue']     =  $data['totalassets'] - $data['totalliabilities'];

        if($request->has('pdf'))
        {
            $pdf = PDF::loadView('admin.reports.balancesheets_pdf', $data, [], [
                'format' => 'a4',
                'orientation' => 'portrait'
            ]);
            return $pdf->stream('balancesheets.pdf');
        }
        else{
            return view('admin.reports.balancesheets', $data);
        }

       
    }


    public function customerdailyreports(Request $request)
    {

        if ($request->date) {
            $data['date'] = $request->date;
            $data['searching'] = "Yes";
            $date = $request->date;

            // Fetch customers with orders on a specific date
            $data['customers'] = User::with([
                'orders' => function ($query) use ($date) {
                    $query->where('date', $date);
                },
                'duePayments' => function ($query) use ($date) {
                    $query->where('date', $date);
                },
                'productDamage' => function ($query) use ($date) {
                    $query->where('date', $date);
                }
            ])
                ->where('type', 'customer')
                //->whereIn('id',[140,9,165,242])
                ->get();
                
            $getyear  = Date('Y',strtotime($request->date));
            $getmonth = Date('m',strtotime($request->date));

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date   = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            // Add monthly sales to each customer
            foreach ($data['customers'] as $customer) {
                $customer->monthly_sales = $customer->orders()
                    ->whereBetween('date', [
                        $start_date,
                        $end_date,
                    ])
                    ->sum('net_amount');
            }
        } else {
            $data['date'] = '';
            $data['searching'] = "No";
        }

        if ($request->has('search')) {
            return view('admin.reports.customerdailyreports', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.reports.customerdailyreports_pdf', $data, [], [
                'format' => 'a4',
                'orientation' => 'landscape'
            ]);
            return $pdf->stream('customer_daily_reports.pdf');
        } else {
            return view('admin.reports.customerdailyreports', $data);
        }
    }
}
