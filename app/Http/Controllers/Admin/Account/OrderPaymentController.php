<?php

namespace App\Http\Controllers\Admin\Account;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OrderPaymentExport;
use App\Http\Controllers\Controller;
use App\Models\Account\OrderPayment;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Account\TransactionHistory;

class OrderPaymentController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.orderpayment.list');

        $data['customers'] = User::where('type', 'customer')->get(['id', 'uid', 'name']);
        $query = OrderPayment::query();

        if ($request->customer_id) {
            $data['customer_id'] = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(48));
        }

        $data['orderpayments'] = $query->latest()->paginate(100);

        if ($request->has('search')) {
            return view('admin.accounts.orderpayments.view', $data);
        } elseif ($request->has('pdf')) {
            $data['orderpayments'] = $query->latest()->get();
            $pdf =  Pdf::loadView('admin.accounts.orderpayments.orderpayment_pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('orderpayment_list.pdf');
        } elseif ($request->has('excel')) {
            $data['orderpayments'] = $query->latest()->get();
            return Excel::download(new OrderPaymentExport($data), 'Orderpayment_list.xlsx');
        } else {
            return view('admin.accounts.orderpayments.view', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.orderpayment.create');

        $data['buyers'] = User::active()->where('type', 'customer')->with('unpaidorders')->get();
        return view('admin.accounts.orderpayments.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.orderpayment.store');

        $request->validate([
            'amount'        => 'required',
            'customer_id'   => 'required',
            'order_id'      => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $orderpayment = OrderPayment::create(array_merge($request->except(['_token']), [
            'date'      => $request->date ? $request->date : Date('Y-m-d'),
            'amount'    => bn2en($request->amount),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Paid'
        ]));

        $orderpayment->tnx_no = 'OP000' . $orderpayment->id;
        $orderpayment->save();

        $findorder = Order::find($request->order_id);

        $orderpaidamount = $findorder->paidamount($findorder->id);

        if ($request->amount  >= $findorder->customer_due) {
            $findorder->payment_status          = "Paid";
            $findorder->paid_amount             = $orderpaidamount;
            $findorder->order_due               = $findorder->order_due -  $orderpaidamount;
            $findorder->customer_due            = $findorder->customer_due - $orderpaidamount;
        } else {
            $findorder->payment_status          = "Partial";
            $findorder->paid_amount             = $findorder->paidamount($findorder->id);
            $findorder->order_due               = $findorder->order_due -  $orderpaidamount;
            $findorder->customer_due            = $findorder->customer_due - $orderpaidamount;
        }

        $findorder->save();


        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $orderpayment->tnx_no;
        $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 2; // Order Payment
        $transactionhistory->module_invoice_id = $orderpayment->id;
        $transactionhistory->amount = bn2en($request->amount);
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->customer_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();


        $account = Account::find($request->account_id);
        $accountbalance = $account->balance($account->id) - bn2en($request->amount);

        // transaction hoar ager balance
        $transactionhistory->pre_balance = $accountbalance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        // Account Balance Update
        $account->main_balance = $accountbalance + bn2en($request->amount);
        $account->save();


        // transaction hoar porer balance
        $transactionhistory->per_balance = $accountbalance + bn2en($request->amount);
        $transactionhistory->save();

        $notify[] = ['success', 'Order Payment successfully Added'];
        return  back()->withNotify($notify);
    }

    public function show(OrderPayment $orderpayment)
    {
        Gate::authorize('admin.orderpayment.show');
        return view('admin.accounts.orderpayments.show', compact('orderpayment'));
    }

    public function edit(OrderPayment $orderpayment)
    {
        Gate::authorize('admin.orderpayment.edit');
        $data['customers'] = User::active()->where('type', 'customer')->with('unpaidorders')->get();
        return view('admin.accounts.orderpayments.edit', compact('orderpayment'), $data);
    }

    public function update(Request $request, OrderPayment $orderpayment)
    {
        Gate::authorize('admin.orderpayment.update');
        $request->validate([
            'amount' => 'required',
            'buyer_id' => 'required',
            'order_id' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'buyer_account_id' => 'required',
        ]);

        $orderpayment->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Order Payment successfully Updated'];
        return to_route('admin.orderpayment.index')->withNotify($notify);
    }

    public function destroy(OrderPayment $orderpayment)
    {
        // Gate::authorize('admin.orderpayment.delete');
        $orderpayment->delete();
        $notify[] = ['success', "Order Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
