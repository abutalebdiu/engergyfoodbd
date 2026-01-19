<?php

namespace App\Http\Controllers\Admin\Expense;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Expense\Expense;
use App\Http\Controllers\Controller;
use App\Models\Account\TransactionHistory;
use App\Models\Expense\ExpensePaymentHistory;
use Illuminate\Support\Facades\Gate;
use PDF;

class ExpensePaymentHistoryController extends Controller
{

    public function index(Request  $request)
    {
        Gate::authorize('admin.expensepaymenthistory.list');

        $query = ExpensePaymentHistory::query();


        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subDays(10));
        }

        $data['expensespaymenthistories'] = $query->paginate(100);

        if ($request->has('search')) {
            return view('admin.expenses.expensespaymenthistories.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.expenses.expensespaymenthistories.view_pdf', $data);
            return $pdf->stream('expense_list.pdf');
        } elseif ($request->has('excel')) {
            return view('admin.expenses.expensespaymenthistories.view', $data);
        } else {
            return view('admin.expenses.expensespaymenthistories.view', $data);
        }
    }


    public function create()
    {
        Gate::authorize('admin.expensepaymenthistory.create');

        return view('admin.expenses.expensespaymenthistories.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.expensepaymenthistory.store');

        $expense = Expense::find($request->expense_id);

        $expensepayment = new ExpensePaymentHistory();
        $expensepayment->ex_invoice_no = $expense->invoice_no;
        $expensepayment->date = $request->date ? $request->date : Date('Y-m-d');
        $expensepayment->expense_id = $expense->id;
        $expensepayment->amount = $request->amount;
        $expensepayment->payment_method_id = $request->payment_method_id;
        $expensepayment->account_id = $request->account_id;
        $expensepayment->entry_id = auth("admin")->user()->id;
        $expensepayment->save();

        if ($expense->total_amount == $expense->expensepayment->sum('amount')) {
            $expense->status = 'Paid';
            $expense->save();
        } else {
            $expense->status = 'Partial';
            $expense->save();
        }

        $expensepayment->txt_no = "OE000" . $expense->id;
        $expensepayment->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $expensepayment->txt_no;
        $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 6; // Office Expense Payment
        $transactionhistory->module_invoice_id = $expensepayment->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $account = Account::find($request->account_id);
        $transactionhistory->pre_balance = $account->main_balance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();


        $account->main_balance = $account->main_balance - $request->amount;
        $account->save();

        // transaction hoar porer balance
        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();

        $notify[] = ['success', 'ExpensePaymentHistory successfully Added'];
        return back()->withNotify($notify);
    }

    public function show(ExpensePaymentHistory $expensepaymenthistory)
    {
        Gate::authorize('admin.expensepaymenthistory.show');
        return view('admin.expenses.expensespaymenthistories.show', compact('expensepaymenthistory'));
    }

    public function edit(ExpensePaymentHistory $expensepaymenthistory)
    {
        Gate::authorize('admin.expensepaymenthistory.edit');
        return view('admin.expenses.expensespaymenthistories.edit', compact('expensepaymenthistory'));
    }

    public function update(Request $request, ExpensePaymentHistory $expensepaymenthistory)
    {
        // Gate::authorize('admin.expensepaymenthistory.update');
        $request->validate([
            'amount' => 'required',
        ]);

        $expensepaymenthistory->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Expense Payment History successfully Updated'];
        return to_route('admin.expensepaymenthistory.index')->withNotify($notify);
    }

    public function destroy(ExpensePaymentHistory $expensepaymenthistory)
    {

        //  Gate::authorize('admin.expensepaymenthistory.destroy');
        $expensepaymenthistory->delete();
        $notify[] = ['success', "Expense Payment History deleted successfully"];
        return back()->withNotify($notify);
    }
}
