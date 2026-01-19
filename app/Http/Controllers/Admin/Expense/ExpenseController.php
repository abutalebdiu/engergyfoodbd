<?php

namespace App\Http\Controllers\Admin\Expense;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\HR\Employee;
use Illuminate\Http\Request;
use App\Exports\ExpenseExport;
use App\Models\Account\Account;
use App\Models\Expense\Expense;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Expense\ExpenseDetail;
use App\Models\Expense\ExpenseCategory;
use App\Models\Account\TransactionHistory;
use App\Models\Expense\ExpensePaymentHistory;

class ExpenseController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.expense.list');
         $data['expensecategories'] = ExpenseCategory::active()->get();
         
         
        $query = Expense::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(40));
        }

        if ($request->category_id) {
            $data['category_id'] = $request->category_id;
            $query->where('category_id', $request->category_id);
        }

        $data['expenses'] =  $query->latest()->get();
        
        
        if($request->ajax()){
            return response()->json([
                "status" => true,
                "message" => "Data view",
                "html"=> view('admin.expenses.expenses.inc.expense_table', $data)->render(),
            ]);
        }
       
        if ($request->has('search')) {
            return view('admin.expenses.expenses.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.expenses.expenses.expense_pdf', $data);
            return $pdf->stream('expense_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new ExpenseExport($data), 'expense_list.xlsx');
        } else {
            return view('admin.expenses.expenses.view', $data);
        }


        return view('admin.expenses.expenses.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.expense.create');

        $data['employees'] = Employee::active()->get();
        $data['expensecategories'] = ExpenseCategory::active()->get();
        return view('admin.expenses.expenses.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.expense.store');

        $request->validate([
            'category_id' => 'required',
            'total_amount' => 'required',
            'expense_date' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $expense = Expense::create(array_merge($request->except('_token', 'name', 'qty', 'amount', 'payment_method_id', 'account_id', 'pay_amount'), [
                'entry_id' => auth('admin')->user()->id,
                'status' => 'Unpaid'
            ]));

            if (!empty($request->input('name'))) {
                foreach ($request->input('name') as $key => $value) {
                    $expensedatail = new ExpenseDetail();
                    $expensedatail->expense_id = $expense->id;
                    $expensedatail->name        = $request->input('name')[$key];
                    $expensedatail->qty         = bn2en($request->input('qty')[$key]);
                    $expensedatail->amount      = bn2en($request->input('amount')[$key]);
                    $expensedatail->entry_id = auth('admin')->user()->id;
                    $expensedatail->status = 'Active';
                    $expensedatail->save();
                }
            }

            $expense->invoice_no = "EXP000" . $expense->id;
            $expense->save();


            if ($request->payment_method_id && $request->account_id && $request->pay_amount) {

                $expensepayment = new ExpensePaymentHistory();
                $expensepayment->ex_invoice_no  = $expense->invoice_no;
                $expensepayment->date           = $request->expense_date;
                $expensepayment->expense_id     = $expense->id;
                $expensepayment->amount         = bn2en($request->pay_amount);
                $expensepayment->payment_method_id = $request->payment_method_id;
                $expensepayment->account_id = $request->account_id;
                $expensepayment->entry_id = auth("admin")->user()->id;
                $expensepayment->save();

                $expensepayment->txt_no = "OE000" . $expense->id;
                $expensepayment->save();

                $transactionhistory = new TransactionHistory();
                $transactionhistory->invoice_no = $expensepayment->txt_no;
                $transactionhistory->reference_no = '';
                $transactionhistory->module_id = 6; // Office Expense Payment
                $transactionhistory->module_invoice_id = $expensepayment->id;
                $transactionhistory->amount = bn2en($request->pay_amount);
                $transactionhistory->cdf_type = 'debit';
                $transactionhistory->payment_method_id = $request->payment_method_id;
                $transactionhistory->account_id = $request->account_id;
                $transactionhistory->note = $request->note;
                $transactionhistory->save();

                $account = Account::find($request->account_id);
                $transactionhistory->pre_balance = $account->main_balance;
                $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
                $transactionhistory->save();


                $account->main_balance = $account->main_balance - bn2en($request->pay_amount);
                $account->save();

                // transaction hoar porer balance
                $transactionhistory->per_balance = $account->main_balance;
                $transactionhistory->save();

                if (bn2en($request->pay_amount) == $expense->total_amount) {
                    $expense->status = 'Paid';
                    $expense->save();
                } else {
                    $expense->status = 'Partial';
                    $expense->save();
                }
            }

            DB::commit();
            $notify[] = ['success', 'Expense successfully Added'];
            return to_route('admin.expense.index')->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();
            $notify[] = ['error', 'Something Wrong'];
            return to_route('admin.expense.create')->withNotify($notify);
        }
    }

    public function show(Expense $expense)
    {
        Gate::authorize('admin.expense.show');
        return view('admin.expenses.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        Gate::authorize('admin.expense.edit');

        $data['employees'] = Employee::active()->get();
        $data['expensecategories'] = ExpenseCategory::active()->get();
        return view('admin.expenses.expenses.edit', compact('expense'), $data);
    }

    public function update(Request $request, Expense $expense)
    {
        Gate::authorize('admin.expense.update');

        $request->validate([
            'category_id'   => 'required',
            'total_amount'  => 'required',
            'expense_date'  => 'required',
        ]);

        $expense->update(array_merge($request->except('_token', 'name', 'qty', 'amount'), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now()
        ]));

        if (!empty($request->input('name'))) {
            ExpenseDetail::where('expense_id', $expense->id)->delete();
            foreach ($request->input('name') as $key => $value) {
                $expensedatail = new ExpenseDetail();
                $expensedatail->expense_id = $expense->id;
                $expensedatail->name = $request->input('name')[$key];
                $expensedatail->qty = $request->input('qty')[$key];
                $expensedatail->amount = $request->input('amount')[$key];
                $expensedatail->entry_id = auth('admin')->user()->id;
                $expensedatail->status = 'Active';
                $expensedatail->save();
            }
        }


        $notify[] = ['success', 'Expense successfully Updated'];
        return to_route('admin.expense.index')->withNotify($notify);
    }

    public function destroy(Expense $expense)
    {
        ExpenseDetail::where('expense_id', $expense->id)->delete();
        ExpensePaymentHistory::where('expense_id', $expense->id)->delete();
        $expense->delete();
        $notify[] = ['success', "Expense deleted successfully"];
        return back()->withNotify($notify);
    }
}
