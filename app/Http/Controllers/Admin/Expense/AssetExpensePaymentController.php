<?php

namespace App\Http\Controllers\Admin\Expense;

use Exception;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Expense\AssetExpense;
use App\Models\Account\TransactionHistory;
use App\Models\Expense\AssetExpensePayment;
use PDF;

class AssetExpensePaymentController extends Controller
{

    public function index(Request $request)
    {
        $query = AssetExpensePayment::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->asset_expense_id) {
            $query->where('asset_expense_id', $request->asset_expense_id);
        }


        $data['assetexpensepayments'] = $query->paginate(100);



        $data['assetexpenses'] = AssetExpense::active()->get();

        if ($request->has('search')) {
            return view('admin.expenses.assetexpensepayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.expenses.assetexpensepayments.view_pdf', $data);
            return $pdf->stream('asset_expense_list.pdf');
        } elseif ($request->has('excel')) {
            return view('admin.expenses.expensespaymenthistories.view', $data);
        } else {
            return view('admin.expenses.assetexpensepayments.view', $data);
        }
    }


    public function create()
    {
        $assetexpenses = AssetExpense::get();
        return view('admin.expenses.assetexpensepayments.create', compact('assetexpenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_expense_id'  => 'required|exists:asset_expenses,id',
            'account_id'        => 'required|exists:accounts,id',
            'date'              => 'required',
            'amount'            => 'required',
        ]);

        DB::beginTransaction();
        try {

            $assetexpensespayment = AssetExpensePayment::create(array_merge($request->all(), [
                'year'      => Date('Y'),
                'entry_id'  => auth('admin')->user()->id,
                'status'    => 'Paid'
            ]));

            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no     = '';
            $transactionhistory->reference_no   = '';
            $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
            $transactionhistory->module_id      = 21; // Asset Expense Payment
            $transactionhistory->module_invoice_id = $assetexpensespayment->id;
            $transactionhistory->amount         =  bn2en($request->amount);
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
            $notify[] = ['success', 'Asset Expense Payment successfully Added'];

            return to_route('admin.assetexpensepayment.index')->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();

            $notify[] = ['error', 'Something went wrong'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Asset Expense Payment successfully Added'];
        return to_route('admin.assetexpensepayment.index')->withNotify($notify);
    }


    public function edit(AssetExpensePayment $assetexpensepayment)
    {
        $assetexpenses          = AssetExpense::get();
        return view('admin.expenses.assetexpensepayments.edit', compact('assetexpenses'), compact('assetexpensepayment'));
    }


    public function update(Request $request, AssetExpensePayment $assetexpensepayment)
    {
        // Gate::authorize('admin.expensepaymenthistory.update');
        $request->validate([
            'asset_expense_id'  => 'required|exists:asset_expenses,id',
            'account_id'        => 'required|exists:accounts,id',
            'date'              => 'required',
            'amount'            => 'required',
        ]);

        $assetexpensepayment->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Asset Expense Payment History successfully Updated'];
        return to_route('admin.assetexpensepayment.index')->withNotify($notify);
    }

    public function destroy(AssetExpensePayment $assetexpensepayment)
    {

        //  Gate::authorize('admin.expensepaymenthistory.destroy');
        $assetexpensepayment->delete();

        $notify[] = ['success', "Expense Payment History deleted successfully"];
        return back()->withNotify($notify);
    }
}
