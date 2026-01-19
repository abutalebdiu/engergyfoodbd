<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\SupplierAdvance;
use App\Models\Account\TransactionHistory;

class SupplierAdvanceController extends Controller
{

    public function index()
    {
        $data['supplieradvances'] = SupplierAdvance::latest()->get();
        return view('admin.accounts.supplieradvances.view',$data);
    }

    public function create()
    {
        $data['suppliers'] =  User::where('type', 'supplier')->get(['id', 'name']);
        return view('admin.accounts.supplieradvances.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $supplieradvance = SupplierAdvance::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id
        ]));


        $supplieradvance->tnx_no = 'SA000' . $supplieradvance->id;
        $supplieradvance->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $supplieradvance->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 16; // Customer Advance Payment
        $transactionhistory->module_invoice_id = $supplieradvance->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->supplier_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        $account = Account::find($request->account_id);
        $transactionhistory->pre_balance =  $account->main_balance;
        $transactionhistory->save();

        $account->main_balance = $account->main_balance - $request->amount;
        $account->save();

        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();


        $notify[] = ['success', 'Supplier Advance successfully Added'];
        return to_route('admin.supplieradvance.index')->withNotify($notify);
    }

    public function show(SupplierAdvance $supplieradvance)
    {
         return view('admin.accounts.supplieradvances.show',compact('supplieradvance'));
    }

    public function edit(SupplierAdvance $supplieradvance)
    {
        $data['suppliers'] =  User::where('type', 'supplier')->get(['id', 'name']);
        return view('admin.accounts.supplieradvances.edit',compact('supplieradvance'),$data);
    }

    public function update(Request $request, SupplierAdvance $supplieradvance)
    {
        $request->validate([
            'supplier_id' => 'required',
        ]);

        $supplieradvance->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Supplier Advance successfully Updated'];
        return to_route('admin.supplieradvance.index')->withNotify($notify);
    }

    public function destroy(SupplierAdvance $supplieradvance)
    {
        $supplieradvance->delete();
        $notify[] = ['success', "Supplier Advance deleted successfully"];
        return back()->withNotify($notify);
    }
}
