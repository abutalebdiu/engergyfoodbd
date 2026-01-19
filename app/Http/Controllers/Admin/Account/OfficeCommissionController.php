<?php

namespace App\Http\Controllers\Admin\Account;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\BuyerAccount;
use App\Models\Account\OfficeCommission;
use App\Models\Account\TransactionHistory;


class OfficeCommissionController extends Controller
{

    public function index()
    {
        $data['officecommissions'] = OfficeCommission::active()->get();

        return view('admin.accounts.officecommissions.view',$data);
    }

    public function create()
    {
        $data['buyers'] = User::active()->where('type', 'buyer')->with('buyeraccounts')->get();
        return view('admin.accounts.officecommissions.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required',
            'account_id'        => 'required',
            'buyer_account_id'  => 'required', 
            'amount'            => 'required',
        ]);

        $buyeraccount = BuyerAccount::find($request->buyer_account_id);

        $officecommission =  OfficeCommission::create(array_merge($request->all(), [
            'from_account_id' => $buyeraccount->account_id,
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Settled'
        ]));

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = '';
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 14; // Office Commission
        $transactionhistory->module_invoice_id = $officecommission->id;
        $transactionhistory->amount = $request->amount;
       
        
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->buyer_account_id = $request->buyer_account_id;
        $transactionhistory->client_id = $request->buyer_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        // Mother Accoun e TK barbe
        $account = Account::find($request->account_id);
        $account->main_balance = $account->balance($account->id) + $request->amount;
        $account->save();

        // Buyer Accoun theke TK kombe
        $buyeraccount = BuyerAccount::find($request->buyer_account_id);
        $buyeraccount->balance = $buyeraccount->balance($buyeraccount->id,$buyeraccount->buyer_id) - $request->amount;
        $buyeraccount->save();

         

        $notify[] = ['success', 'Office Commission successfully Added'];
        return to_route('admin.officecommission.index')->withNotify($notify);
    }

    public function show(OfficeCommission $officecommission)
    {
        return view('admin.accounts.officecommissions.show',compact('officecommission'));
    }

    public function edit(OfficeCommission $officecommission)
    {
        $data['buyers'] = User::active()->where('type', 'buyer')->with('buyeraccounts')->get();
        return view('admin.accounts.officecommissions.edit',compact('officecommission'),$data);
    }

    public function update(Request $request, OfficeCommission $officecommission)
    {
        $request->validate([
            'payment_method_id' => 'required',
            'account_id'        => 'required',
            'buyer_account_id'  => 'required', 
            'amount'            => 'required',
        ]);

        $buyeraccount = BuyerAccount::find($request->buyer_account_id);

        $officecommission->update(array_merge($request->all(), [
            'from_account_id' => $buyeraccount->account_id,
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Office Commission successfully Updated'];
        return to_route('admin.officecommission.index')->withNotify($notify);
    }

    public function destroy(OfficeCommission $officecommission)
    {
        $officecommission->delete();
        $notify[] = ['success', "Office Commission deleted successfully"];
        return back()->withNotify($notify);
    }
}
