<?php

namespace App\Http\Controllers\Admin\Account;


use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\CustomerAdvance;
use App\Models\Account\TransactionHistory;

class CustomerAdvanceController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.customeradvance.list');

        $data['customeradvances'] = CustomerAdvance::latest()->get();
        return view('admin.accounts.customeradvances.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.customeradvance.create');

        $data['customers'] =  User::where('type', 'customer')->get(['id', 'name']);
        return view('admin.accounts.customeradvances.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.customeradvance.store');

        $request->validate([
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $customeradvance = CustomerAdvance::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id
        ]));


        $customeradvance->tnx_no = 'CA000' . $customeradvance->id;
        $customeradvance->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $customeradvance->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 15; // Customer Advance Payment
        $transactionhistory->module_invoice_id = $customeradvance->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->customer_id;
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


        $notify[] = ['success', 'Customer Advance successfully Added'];
        return to_route('admin.customeradvance.index')->withNotify($notify);
    }

    public function show(CustomerAdvance $customerAdvance)
    {
        Gate::authorize('admin.customeradvance.show');

        return view('admin.accounts.customeradvances.show', compact('customerAdvance'));
    }

    public function edit(CustomerAdvance $customeradvance)
    {
        Gate::authorize('admin.customeradvance.edit');
        $data['customers'] =  User::where('type', 'customer')->get(['id', 'name']);
        return view('admin.accounts.customeradvances.edit', compact('customeradvance'), $data);
    }

    public function update(Request $request, CustomerAdvance $customeradvance)
    {
        Gate::authorize('admin.customeradvance.update');
        $request->validate([
            'customer_id' => 'required',
        ]);

        $customeradvance->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Customer Advance successfully Updated'];
        return to_route('admin.customeradvance.index')->withNotify($notify);
    }

    public function destroy(CustomerAdvance $customerAdvance)
    {
        Gate::authorize('admin.customeradvance.destroy');

        $customerAdvance->delete();
        $notify[] = ['success', "Customer Advance deleted successfully"];
        return back()->withNotify($notify);
    }
}
