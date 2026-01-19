<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Account\Deposit;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;
use PDF;

class DepositController extends Controller
{

    public function index(Request $request)
    {
        $query = Deposit::query();
    
        // Handle date filtering
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
    
        // Fetch deposits
        $data['deposits'] = $query->get();
    
        // Check if it's an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'view' => view('admin.accounts.deposits.ajax_view', $data)->render(),
                'total' => $data['deposits']->sum('amount'),
            ]);
        }
    
        // Handle PDF download
        if ($request->has('pdf')) {
            $pdf = Pdf::loadView('admin.accounts.deposits.deposit_pdf', $data);
            return $pdf->stream('deposit_list.pdf');
        }
    
        // Default view
        return view('admin.accounts.deposits.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.deposit.create');
        return view('admin.accounts.deposits.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.deposit.store');
        $request->validate([
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
        ]);

        $deposit = Deposit::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $deposit->tnx_no = 'DP000' . $deposit->id;
        $deposit->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $deposit->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 7; // Deposit Payment
        $transactionhistory->module_invoice_id = $deposit->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        $account = Account::find($request->account_id);
        $transactionhistory->pre_balance =  $account->main_balance;
        $transactionhistory->save();

        $account->main_balance = $account->main_balance + $request->amount;
        $account->save();

        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();

        $notify[] = ['success', 'Deposit successfully Added'];
        return to_route('admin.deposit.index')->withNotify($notify);
    }

    public function show(Deposit $deposit)
    {
        Gate::authorize('admin.deposit.show');
        return view('admin.accounts.deposits.show', compact('deposit'));
    }

    public function edit(Deposit $deposit)
    {
        Gate::authorize('admin.deposit.edit');
        $data['buyers'] = User::active()->where('type', 'buyer')->with('buyeraccounts')->get();
        return view('admin.accounts.deposits.edit', compact('deposit'), $data);
    }

    public function update(Request $request, Deposit $deposit)
    {
        Gate::authorize('admin.deposit.update');
        $request->validate([
            'payment_method_id' => 'required',
            'account_id'        => 'required',
            'amount'            => 'required',
        ]);

        $deposit->update(array_merge($request->except('_token', 'attachment'), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Deposit successfully Updated'];
        return to_route('admin.deposit.index')->withNotify($notify);
    }

    public function destroy(Deposit $deposit)
    {
        Gate::authorize('admin.deposit.destroy');
        $deposit->delete();
        $notify[] = ['success', "Deposit deleted successfully"];
        return back()->withNotify($notify);
    }
}
