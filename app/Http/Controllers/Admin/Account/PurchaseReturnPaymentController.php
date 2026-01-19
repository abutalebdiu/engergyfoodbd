<?php


namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Order\PurchaseReturn;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;
use App\Models\Account\PurchaseReturnPayment;


class PurchaseReturnPaymentController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.purchasereturnpayment.list');

        $data['purchasereturnpayments'] = PurchaseReturnPayment::latest()->get();
        return view('admin.accounts.purchasereturnpayments.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.purchasereturnpayment.create');
        return view('admin.accounts.purchasereturnpayments.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.purchasereturnpayment.store');

        $request->validate([
            'amount'            => 'required',
            'supplier_id'       => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required'
        ]);

        $purchasereturnpayment = PurchaseReturnPayment::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id
        ]));


        $sumpayment = PurchaseReturnPayment::where('purchase_return_id', $request->purchase_return_id)->sum('amount');
        $purchasereturn = PurchaseReturn::find($request->purchase_return_id);
        if ($sumpayment >= $purchasereturn->amount) {
            $purchasereturn->payment_status = "Paid";
            $purchasereturn->save();
        } else {
            $purchasereturn->payment_status = "Partial";
            $purchasereturn->save();
        }

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $purchasereturnpayment->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 4; // Purchase Return Payment
        $transactionhistory->module_invoice_id = $purchasereturnpayment->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->supplier_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $account = Account::find($request->account_id);

        // transaction hoar ager balance
        $transactionhistory->pre_balance = $account->main_balance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();


        $account->main_balance = $account->main_balance + $request->amount;
        $account->save();

        // transaction hoar porer balance
        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();

        $notify[] = ['success', 'Purchase Return Payment successfully Added'];
        return to_route('admin.purchasereturnpayment.index')->withNotify($notify);
    }

    public function show(PurchaseReturnPayment $purchasereturnpayment)
    {
        Gate::authorize('admin.purchasereturnpayment.show');
        return view('admin.accounts.purchasereturnpayments.show', compact('purchasereturnpayment'));
    }

    public function edit(PurchaseReturnPayment $purchasereturnpayment)
    {
        Gate::authorize('admin.purchasereturnpayment.edit');
        return view('admin.accounts.purchasereturnpayments.edit', compact('purchasereturnpayment'));
    }

    public function update(Request $request, PurchaseReturnPayment $purchasereturnpayment)
    {
        Gate::authorize('admin.purchasereturnpayment.update');
        $request->validate([
            'name' => 'required',
        ]);



        $notify[] = ['success', 'Purchase Return Payment successfully Updated'];
        return to_route('admin.purchasereturnpayment.index')->withNotify($notify);
    }

    public function destroy(PurchaseReturnPayment $purchasereturnpayment)
    {
        Gate::authorize('admin.purchasereturnpayment.destroy');
        $purchasereturnpayment->delete();
        $notify[] = ['success', "Purchase Return Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
