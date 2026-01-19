<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order\Purchse;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\TransactionHistory;
use App\Models\Account\OrderSupplierPayment;
use Illuminate\Support\Facades\Gate;

class OrderSupplierPaymentController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.ordersupplierpayment.list');

        $data['ordersupplierpayments'] = OrderSupplierPayment::active()->get();
        return view('admin.accounts.ordersupplierpayments.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.ordersupplierpayment.create');
        $data['suppliers'] = User::active()->where('type', 'supplier')->with('supplierunpaidorders')->get();
        return view('admin.accounts.ordersupplierpayments.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.ordersupplierpayment.store');
        $request->validate([
            'amount'            => 'required',
            'supplier_id'       => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required'
        ]);

        $ordersupplierpayment = OrderSupplierPayment::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Paid'
        ]));

        $ordersupplierpayment->tnx_no = 'SP000' . $ordersupplierpayment->id;
        $ordersupplierpayment->save();

        $sumpayment = OrderSupplierPayment::where('purchase_id', $request->purchase_id)->sum('amount');
        $purchase = Purchse::find($request->purchase_id);
        if ($sumpayment >= $purchase->amount) {
            $purchase->payment_status = "Paid";
            $purchase->save();
        } else {
            $purchase->payment_status = "Partial";
            $purchase->save();
        }

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $ordersupplierpayment->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 3; // Supplier  Payment
        $transactionhistory->module_invoice_id = $ordersupplierpayment->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'debit';
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


        $account->main_balance = $account->main_balance - $request->amount;
        $account->save();

        // transaction hoar ager balance
        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();


        $notify[] = ['success', 'Order Supplier Payment successfully Added'];
        return  back()->withNotify($notify);
    }

    public function show(OrderSupplierPayment $ordersupplierpayment)
    {
        Gate::authorize('admin.ordersupplierpayment.show');
        return view('admin.accounts.ordersupplierpayments.show', compact('orderSupplierPayment'));
    }

    public function edit(OrderSupplierPayment $ordersupplierpayment)
    {
        Gate::authorize('admin.ordersupplierpayment.edit');
        return view('admin.accounts.ordersupplierpayments.edit', compact('orderSupplierPayment'));
    }

    public function update(Request $request, OrderSupplierPayment $ordersupplierpayment)
    {
        Gate::authorize('admin.ordersupplierpayment.update');
        $request->validate([
            'name' => 'required',
        ]);

        $ordersupplierpayment->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Order Supplier Payment successfully Updated'];
        return to_route('admin.ordersupplierpayment.index')->withNotify($notify);
    }

    public function destroy(OrderSupplierPayment $ordersupplierpayment)
    {
        Gate::authorize('admin.ordersupplierpayment.delete');
        $ordersupplierpayment->delete();
        $notify[] = ['success', "Order Supplier Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
