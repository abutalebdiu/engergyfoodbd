<?php

namespace App\Http\Controllers\Admin\Expense;

use Exception;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse\Transport;
use App\Http\Controllers\Controller;
use App\Models\Expense\TransportExpense;
use App\Models\Account\TransactionHistory;
use App\Models\Expense\TransportExpensePayment;
use PDF;
use Carbon\Carbon;

class TransportExpensePaymentController extends Controller
{

    public function index(Request $request)
    {
        $data['transportexpenses'] = TransportExpense::get();

        $query = TransportExpensePayment::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            // $query->whereBetween('date', [Carbon::parse(date('Y-m-01')),Carbon::parse(date('Y-m-t'))]);
            $query->where('created_at', '>=', Carbon::now()->subDays(5));
        }

        if ($request->transport_expense_id) {
            $query->where('transport_expense_id', $request->transport_expense_id);
        }

        if ($request->orderby == "DateAsc") {
            $query->orderBy('date', 'asc');
        } elseif ($request->orderby == "DateDesc") {
            $query->orderBy('date', 'desc');
        } elseif ($request->orderby == "Category") {
            $query->orderBy('transport_expense_id', 'asc')->orderby('date', 'asc');
        } else {
            $query->orderBy('date', 'desc');
        }

        $data['transportexpensepayments'] = $query->get();

        if ($request->has('search')) {
            return view('admin.expenses.transportexpensespayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.expenses.transportexpensespayments.view_pdf', $data);
            return $pdf->stream('transports_expense_list.pdf');
        } elseif ($request->has('excel')) {
            return view('admin.expenses.transportexpensespayments.view', $data);
        } else {
            return view('admin.expenses.transportexpensespayments.view', $data);
        }
    }


    public function create()
    {
        $transportexpenses = TransportExpense::get();
        return view('admin.expenses.transportexpensespayments.create', compact('transportexpenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_expense_id' => 'required|exists:transport_expenses,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required',
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $transportexpensepayment = TransportExpensePayment::create(array_merge($request->all(), [
                'year'      => Date('Y'),
                'entry_id'  => auth('admin')->user()->id,
                'status'    => 'Paid'
            ]));

            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no     = '';
            $transactionhistory->reference_no   = '';
            $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
            $transactionhistory->module_id      = 23; // Transport Expense Payment
            $transactionhistory->module_invoice_id = $transportexpensepayment->id;
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


            $notify[] = ['success', 'Transport Expense Payment successfully Added'];

            return to_route('admin.transportexpensepayment.index')->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();

            $notify[] = ['error', 'Something went wrong'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Transport Expense Payment successfully Added'];
        return to_route('admin.transportexpensepayment.index')->withNotify($notify);
    }


    public function edit(TransportExpensePayment $transportexpensepayment)
    {
        $transportexpenses = TransportExpense::get();
        return view('admin.expenses.transportexpensespayments.edit', compact('transportexpenses', 'transportexpensepayment'));
    }


    public function update(Request $request, TransportExpensePayment $transportexpensepayment)
    {
        // Gate::authorize('admin.expensepaymenthistory.update');
        $request->validate([
            'transport_expense_id' => 'required|exists:transport_expenses,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required',
            'amount' => 'required',
        ]);

        $transportexpensepayment->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Transport Expense Payment History successfully Updated'];
        return to_route('admin.transportexpensepayment.index')->withNotify($notify);
    }

    public function destroy(TransportExpensePayment $transportexpensepayment)
    {

        //  Gate::authorize('admin.expensepaymenthistory.destroy');
        $transportexpensepayment->delete();

        $notify[] = ['success', "Transport Expense Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
