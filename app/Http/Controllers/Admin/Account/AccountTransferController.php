<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\PaymentMethod;
use App\Models\Account\AccountTransfer;
use App\Models\Account\TransactionHistory;
use Illuminate\Support\Facades\Gate;

class AccountTransferController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.accounttransfer.list');

        $data['accounttransfers'] = AccountTransfer::active()->get();

        return view('admin.accounts.accounttransfers.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.accounttransfer.create');
        return view('admin.accounts.accounttransfers.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.accounttransfer.store');

        $request->validate([
            'account_id'        => 'required',
            'from_account_id'   => 'required',
            'amount'            => 'required',
        ]);

        $accounttransfer = AccountTransfer::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Settled'
        ]));


        // IN Payment
        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = '';
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 2; // Account Balance Transfer
        $transactionhistory->module_invoice_id = $accounttransfer->id;
        $transactionhistory->amount      = $request->amount;
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $account = Account::find($request->account_id);

        $accountbalance = $account->balance($account->id) - $request->amount;

        // transaction hoar ager balance
        $transactionhistory->pre_balance = $accountbalance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        // Account Balance Update
        $account->main_balance = $accountbalance + $request->amount;
        $account->save();

        // transaction hoar porer balance
        $transactionhistory->per_balance = $accountbalance + $request->amount;
        $transactionhistory->save();


        // Out Payment
        $fromtransactionhistory = new TransactionHistory();
        $fromtransactionhistory->invoice_no = '';
        $fromtransactionhistory->reference_no = '';
        $fromtransactionhistory->module_id = 2; // Account Balance Transfer
        $fromtransactionhistory->module_invoice_id = $accounttransfer->id;
        $fromtransactionhistory->amount = $request->amount;
        $fromtransactionhistory->cdf_type = 'debit';
        $fromtransactionhistory->payment_method_id = $request->from_payment_method_id;
        $fromtransactionhistory->account_id = $request->from_account_id;
        $fromtransactionhistory->note = $request->note;
        $fromtransactionhistory->save();

        $fromaccount = Account::find($request->from_account_id);
        $fromaccountbalance = $fromaccount->balance($fromaccount->id) + $request->amount;

        // transaction hoar ager balance
        $fromtransactionhistory->pre_balance = $fromaccountbalance;
        $fromtransactionhistory->txt_no = 'TNH000' . $fromtransactionhistory->id;
        $fromtransactionhistory->save();

        // Account Balance Update
        $fromaccount->main_balance = $fromaccountbalance - $request->amount;
        $fromaccount->save();

        // transaction hoar porer balance
        $fromtransactionhistory->per_balance = $fromaccountbalance - $request->amount;
        $fromtransactionhistory->save();


        $notify[] = ['success', 'Account Transfer successfully Added'];
        return to_route('admin.accounttransfer.index')->withNotify($notify);
    }

    public function show(AccountTransfer $accounttransfer)
    {
        Gate::authorize('admin.accounttransfer.show');
        return view('admin.accounts.accounttransfers.show', compact('accounttransfer'));
    }

    public function edit(AccountTransfer $accounttransfer)
    {
        Gate::authorize('admin.accounttransfer.edit');
        $data['paymentmethods'] = PaymentMethod::get();
        return view('admin.accounts.accounttransfers.edit', compact('accounttransfer'), $data);
    }

    public function update(Request $request, AccountTransfer $accounttransfer)
    {
        Gate::authorize('admin.accounttransfer.update');
        $request->validate([
            'account_id'        => 'required',
            'from_account_id'   => 'required',
            'amount'            => 'required',
        ]);

        $previousamount = $accounttransfer->amount;

        $accounttransfer->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        if (TransactionHistory::where('module_invoice_id', $accounttransfer->id)->where('account_id', $request->account_id)->where('module_id', 2)->where('cdf_type', 'credit')->count() > 0) {
            $toaccount = Account::find($request->account_id);
            $credittransaction = TransactionHistory::where('module_invoice_id', $accounttransfer->id)->where('account_id', $request->account_id)->where('module_id', 2)->where('cdf_type', 'credit')->first();

            $credittransaction->pre_balance = 0;
            $credittransaction->amount  = $request->amount;
            $credittransaction->per_balance = $toaccount->balance($toaccount->id);
            $credittransaction->edit_id  = auth('admin')->user()->id;
            $credittransaction->edit_at  = now();
            $credittransaction->save();


            $toaccount->main_balance = $toaccount->balance($toaccount->id);
            $toaccount->save();
        }


        if (TransactionHistory::where('module_invoice_id', $accounttransfer->id)->where('account_id', $request->from_account_id)->where('module_id', 2)->where('cdf_type', 'debit')->count() > 0) {
            $fromaccount = Account::find($request->from_account_id);
            $debittransaction  = TransactionHistory::where('module_invoice_id', $accounttransfer->id)->where('account_id', $request->from_account_id)->where('module_id', 2)->where('cdf_type', 'debit')->first();

            $debittransaction->pre_balance = 0;
            $debittransaction->amount  = $request->amount;
            $debittransaction->per_balance = $fromaccount->balance($fromaccount->id);
            $debittransaction->edit_id  = auth('admin')->user()->id;
            $debittransaction->edit_at  = now();
            $debittransaction->save();


            $fromaccount->main_balance = $fromaccount->balance($fromaccount->id);
            $fromaccount->save();
        }

        $notify[] = ['success', 'Account Transfer successfully Updated'];
        return to_route('admin.accounttransfer.index')->withNotify($notify);
    }

    public function destroy(AccountTransfer $accounttransfer)
    {
        Gate::authorize('admin.accounttransfer.destroy');
        $accounttransfer->delete();
        $notify[] = ['success', "Account Transfer deleted successfully"];
        return back()->withNotify($notify);
    }
}
