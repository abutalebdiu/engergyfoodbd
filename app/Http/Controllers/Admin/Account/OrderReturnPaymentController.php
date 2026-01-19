<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order\OrderReturn;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderReturnPaymentExport;
use App\Models\Account\OrderReturnPayment;
use App\Models\Account\TransactionHistory;
use Illuminate\Support\Facades\Gate;

class OrderReturnPaymentController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.orderreturnpayment.list');

        $data['customers'] = User::where('type', 'customer')->get(['id', 'name']);
        $query = OrderReturnPayment::query();
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
            return view('admin.accounts.orderreturnpayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  Pdf::loadView('admin.accounts.orderreturnpayments.orderreturnpayment_pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('orderreturnpayment_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new OrderReturnPaymentExport($data), 'Orderreturnpayment_list.xlsx');
        } else {
            return view('admin.accounts.orderreturnpayments.view', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.orderreturnpayment.create');

        return view('admin.OrderReturnPayment.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.orderreturnpayment.store');
        $request->validate([
            'amount'            => 'required',
            'customer_id'       => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required'
        ]);

        $orderreturnpayment = OrderReturnPayment::create(array_merge($request->all(), [
            'date'  => $request->date ? $request->date : date('Y-m-d'),
            'amount' => bn2en($request->amount),
            'entry_id' => auth('admin')->user()->id
        ]));

        $orderreturnpayment->tnx_no = "ORP00" . $orderreturnpayment->id;
        $orderreturnpayment->save();

        $sumpayment = OrderReturnPayment::where('order_return_id', $request->order_return_id)->sum('amount');
        $orderreturn = OrderReturn::find($request->order_return_id);
        if ($sumpayment >= $orderreturn->amount) {
            $orderreturn->payment_status = "Paid";
            $orderreturn->save();
        } else {
            $orderreturn->payment_status = "Partial";
            $orderreturn->save();
        }

        $transactionhistory = new TransactionHistory();
        $transactionhistory->date = $request->date ? $request->date : date('Y-m-d');
        $transactionhistory->invoice_no = $orderreturnpayment->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 10; // Order Return Payment
        $transactionhistory->module_invoice_id = $orderreturnpayment->id;
        $transactionhistory->amount = bn2en($request->amount);
        $transactionhistory->cdf_type = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->customer_id;
        $transactionhistory->note = $request->note;
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

        $notify[] = ['success', 'Order Return Payment successfully Added'];
        return to_route('admin.orderreturnpayment.index')->withNotify($notify);
    }

    public function show(OrderReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.orderreturnpayment.show');
        return view('admin.OrderReturnPayment.show', compact('orderReturnPayment'));
    }

    public function edit(OrderReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.orderreturnpayment.edit');
        return view('admin.OrderReturnPayment.edit', compact('orderReturnPayment'));
    }

    public function update(Request $request, OrderReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.orderreturnpayment.update');
        $request->validate([
            'name' => 'required',
        ]);

        $orderReturnPayment->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'OrderReturnPayment successfully Updated'];
        return to_route('admin.OrderReturnPayment.index')->withNotify($notify);
    }

    public function destroy(OrderReturnPayment $orderReturnPayment)
    {
        Gate::authorize('admin.orderreturnpayment.destroy');
        $orderReturnPayment->delete();
        $notify[] = ['success', "OrderReturnPayment deleted successfully"];
        return back()->withNotify($notify);
    }
}
