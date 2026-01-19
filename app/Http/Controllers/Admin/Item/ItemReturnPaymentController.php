<?php

namespace App\Http\Controllers\Admin\Item;

use App\Models\User;
use App\Models\ItemReturn;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ItemOrderPayment;
use App\Models\ItemReturnPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderReturnPaymentExport;
use App\Models\Account\TransactionHistory;

class ItemReturnPaymentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.itemreturnpayment.list');

        $data['customers'] = User::where('type', 'customer')->get(['id', 'name']);
        $query = ItemReturnPayment::query();
        if ($request->customer_id) {
            $data['customer_id'] = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data['orderreturnpayments'] = $query->latest()->get();


        if ($request->has('search')) {
            return view('admin.items.orderreturnpayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  Pdf::loadView('admin.items.orderreturnpayments.orderreturnpayment_pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('orderreturnpayment_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new OrderReturnPaymentExport($data), 'Orderreturnpayment_list.xlsx');
        } else {
            return view('admin.items.orderreturnpayments.view', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.itemreturnpayment.create');
        return view('admin.items.orderreturnpayments.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.itemreturnpayment.store');

        $request->validate([
            'amount'            => 'required',
            'customer_id'       => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required'
        ]);

        $orderreturnpayment = ItemReturnPayment::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id
        ]));

        $orderreturnpayment->tnx_no = "ORP00" . $orderreturnpayment->id;
        $orderreturnpayment->save();

        $sumpayment = ItemReturnPayment::where('order_return_id', $request->order_return_id)->sum('amount');
        $orderreturn = ItemReturn::find($request->order_return_id);
        if ($sumpayment >= $orderreturn->amount) {
            $orderreturn->payment_status = "Paid";
            $orderreturn->save();
        } else {
            $orderreturn->payment_status = "Partial";
            $orderreturn->save();
        }

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $orderreturnpayment->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 10; // Order Return Payment
        $transactionhistory->module_invoice_id = $orderreturnpayment->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->customer_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $account = Account::find($request->account_id);

        // transaction hoar ager balance
        $transactionhistory->pre_balance = $account->main_balance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();


        $account->main_balance = $account->main_balance - $request->amount;
        $account->save();

        // transaction hoar porer balance
        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();

        $notify[] = ['success', 'Order Return Payment successfully Added'];
        return to_route('admin.itemreturnpayment.index')->withNotify($notify);
    }

    public function show(ItemReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.itemreturnpayment.show');
        return view('admin.OrderReturnPayment.show', compact('orderReturnPayment'));
    }

    public function edit(ItemReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.itemreturnpayment.edit');
        return view('admin.OrderReturnPayment.edit', compact('orderReturnPayment'));
    }

    public function update(Request $request, ItemReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.itemreturnpayment.update');
        $request->validate([
            'name' => 'required',
        ]);

        $orderReturnPayment->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'OrderReturnPayment successfully Updated'];
        return to_route('admin.itemreturnpayment.index')->withNotify($notify);
    }

    public function destroy(ItemReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.itemreturnpayment.destroy');
        $orderReturnPayment->delete();
        $notify[] = ['success', "OrderReturnPayment deleted successfully"];
        return back()->withNotify($notify);
    }
}
