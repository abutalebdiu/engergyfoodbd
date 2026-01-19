<?php

namespace App\Http\Controllers\Admin\Expense;

use PDF;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Models\Account\Account;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Expense\MonthlyExpense;
use App\Models\Account\TransactionHistory;
use App\Models\Expense\MonthlyExpensePayment;


class MonthlyExpensePaymentController extends Controller
{

    public function index(Request $request)
    {
        $data['monthlyexpenses'] = MonthlyExpense::get();

        $query = MonthlyExpensePayment::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        else{
            $query->where('created_at', '>=', Carbon::now()->subDays(10));
        }

        if($request->monthly_expense_id)
        {
            $query->where('monthly_expense_id',$request->monthly_expense_id);
        }

        $data['monthlyexpensepayments'] = $query->latest()->paginate(100);


        if ($request->has('search')) {
             return view('admin.expenses.monthlyexpensepayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.expenses.monthlyexpensepayments.view_pdf', $data);
            return $pdf->stream('monthly_expense_list.pdf');
        } elseif ($request->has('excel')) {
            return view('admin.expenses.monthlyexpensepayments.view', $data);
        } else {
             return view('admin.expenses.monthlyexpensepayments.view', $data);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $monthlyexpenses = MonthlyExpense::get();
        $data['months']  = Month::get();
        return view('admin.expenses.monthlyexpensepayments.create', compact('monthlyexpenses'),$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monthly_expense_id' => 'required|exists:monthly_expenses,id',
            'account_id'    => 'required|exists:accounts,id',
            'date'          => 'required',
            'amount'        => 'required',
        ]);

        DB::beginTransaction();
        try {

            $monthlyexpensepayment = MonthlyExpensePayment::create(array_merge($request->all(), [
                'year'      => Date('Y'),
                'entry_id'  => auth('admin')->user()->id,
                'status'    => 'Paid'
            ]));


            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no     = '';
            $transactionhistory->reference_no   = '';
            $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
            $transactionhistory->module_id      = 22; // Monthly Expense Payment
            $transactionhistory->module_invoice_id = $monthlyexpensepayment->id;
            $transactionhistory->amount         = bn2en($request->amount);
            $transactionhistory->cdf_type       = 'debit';
            $transactionhistory->payment_method_id = $request->payment_method_id;
            $transactionhistory->account_id     = $request->account_id;
            $transactionhistory->note           = $request->note;
            $transactionhistory->save();

            $account = Account::find($request->account_id);
            $accountbalance = $account->balance($account->id) + bn2en($request->amount);

            // transaction hoar ager balance
            $transactionhistory->pre_balance = $accountbalance;
            $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
            $transactionhistory->save();

            // Account Balance Update
            $account->main_balance = $accountbalance - bn2en($request->amount);
            $account->save();


            // transaction hoar porer balance
            $transactionhistory->per_balance = $accountbalance - bn2en($request->amount);
            $transactionhistory->save();
            DB::commit();


            $notify[] = ['success', 'Monthly Expense Payment successfully Added'];
            return back()->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();

            $notify[] = ['error', 'Something went wrong'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Monthly Expense Payment successfully Added'];
        return back()->withNotify($notify);
    }


    public function edit(MonthlyExpensePayment $monthlyexpensepayment)
    {
        $monthlyexpenses        = MonthlyExpense::get();
        $data['months']         = Month::get();
        return view('admin.expenses.monthlyexpensepayments.edit', compact('monthlyexpenses','monthlyexpensepayment'),$data);
    }


    public function update(Request $request, MonthlyExpensePayment $monthlyexpensepayment)
    {
       // Gate::authorize('admin.expensepaymenthistory.update');
        $request->validate([
            'monthly_expense_id'=> 'required|exists:monthly_expenses,id',
            'account_id'        => 'required|exists:accounts,id',
            'date'              => 'required',
            'amount'            => 'required',
        ]);

        $monthlyexpensepayment->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Asset Expense Payment History successfully Updated'];
        return to_route('admin.monthlyexpensepayment.index')->withNotify($notify);
    }

    public function destroy(MonthlyExpensePayment $monthlyexpensepayment)
    {

      //  Gate::authorize('admin.expensepaymenthistory.destroy');
        $monthlyexpensepayment->delete();

        $notify[] = ['success', "Expense Payment History deleted successfully"];
        return back()->withNotify($notify);
    }


}
