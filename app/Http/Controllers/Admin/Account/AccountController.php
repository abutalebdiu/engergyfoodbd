<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\PaymentMethod;
use App\Models\Account\TransactionHistory;
use Illuminate\Support\Facades\Gate;
use App\Models\Report\DailyReport;

class AccountController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.account.list');

        $data['accounts'] = Account::active()->get();

        return view('admin.accounts.accounts.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.account.create');
        $data['paymentmethods'] = PaymentMethod::active()->get();
        return view('admin.accounts.accounts.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.account.store');
        $request->validate([
            'payment_method_id' => 'required',
            'title' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'opening_balance' => 'required',
            'status' => 'required',
        ]);

        $account = Account::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id
        ]));

        if ($request->opening_balance > 0) {

            $account->main_balance = $request->opening_balance;
            $account->save();

            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no = '';
            $transactionhistory->reference_no = '';
            $transactionhistory->module_id = 11; // Opening Balance
            $transactionhistory->module_invoice_id = $account->id;
            $transactionhistory->amount = $request->opening_balance;


            $transactionhistory->cdf_type = 'credit';
            $transactionhistory->payment_method_id = $request->payment_method_id;
            $transactionhistory->account_id = $account->id;
            $transactionhistory->note = 'Opening Balance';
            $transactionhistory->save();

            $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
            $transactionhistory->save();

            $transactionhistory->per_balance =  $request->opening_balance;
            $transactionhistory->save();
        }

        $notify[] = ['success', 'Account successfully Added'];
        return to_route('admin.account.index')->withNotify($notify);
    }

    public function show(Account $account)
    {
        Gate::authorize('admin.account.show');
        return view('admin.accounts.accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        Gate::authorize('admin.account.edit');
        $data['paymentmethods'] = PaymentMethod::active()->get();
        return view('admin.accounts.accounts.edit', compact('account'), $data);
    }

    public function update(Request $request, Account $account)
    {
        Gate::authorize('admin.account.update');
        $request->validate([
            'payment_method_id' => 'required',
            'title' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'status' => 'required',
        ]);

        $account->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));


        $notify[] = ['success', 'Account successfully Updated'];
        return to_route('admin.account.index')->withNotify($notify);
    }

    public function destroy(Account $account)
    {
        Gate::authorize('admin.account.destroy');
        $account->delete();
        $notify[] = ['success', "Account deleted successfully"];
        return back()->withNotify($notify);
    }
    
    public function dayclosereport()
    {
        Gate::authorize('admin.account.show');
        $data['dailyreports'] = DailyReport::latest()->get();
        return view('admin.accounts.accounts.dayclosereport',$data);
    }
}
