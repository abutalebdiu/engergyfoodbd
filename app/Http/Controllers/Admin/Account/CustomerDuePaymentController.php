<?php

namespace App\Http\Controllers\Admin\Account;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\TransactionHistory;
use Illuminate\Support\Facades\Gate;


class CustomerDuePaymentController extends Controller
{

    public function index(Request $request)
    {

        Gate::authorize('admin.customerduepayment.list');

        $data['customerduepayments'] = CustomerDuePayment::latest()->get();

        $data['customers'] = User::where('type', 'customer')->orderby('name', 'asc')->get(['id', 'uid', 'name']);
        $query = CustomerDuePayment::query();

        if ($request->customer_id) {
            $data['customer_id']        = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subDay(7));
        }
        $data['customerduepayments'] = $query->get();

        if ($request->has('search')) {
            return view('admin.accounts.customerduepayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.accounts.customerduepayments.customer_due_payment_pdf', $data);
            return $pdf->stream('customer_due_payment.pdf');
        } elseif ($request->has('excel')) {
            // return Excel::download(new OrderExport($data), 'Order_list.xlsx');
        } else {
            return view('admin.accounts.customerduepayments.view', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.customerduepayment.create');

        $data['customers'] = User::where('type', 'customer')->orderby('name', 'asc')->get(['id', 'uid' ,'name']);
        return view('admin.accounts.customerduepayments.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.customerduepayment.store');

        $request->validate([
            'customer_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        $customerduepayment = CustomerDuePayment::create(array_merge($request->all(), [
            'amount'    => bn2en($request->amount),
            'date'      => $request->date ? $request->date : Date('Y-m-d'),
            'year'      => Date('Y'),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Paid'
        ]));

        $customerduepayment->tnx_no = 'CDP000' . $customerduepayment->id;
        $customerduepayment->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $customerduepayment->tnx_no;
        $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 17; // Order Payment
        $transactionhistory->module_invoice_id = $customerduepayment->id;
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



        $notify[] = ['success', 'Customer Due Payment successfully Added'];
        return to_route('admin.customerduepayment.index')->withNotify($notify);
    }

    public function show(CustomerDuePayment $customerduepayment)
    {
        Gate::authorize('admin.customerduepayment.show');

        return view('admin.accounts.customerduepayments.show', compact('customerduepayment'));
    }

    public function edit(CustomerDuePayment $customerduepayment)
    {
        Gate::authorize('admin.customerduepayment.edit');
        $data['customers'] = User::where('type', 'customer')->orderby('name', 'asc')->get(['id', 'name']);
        return view('admin.accounts.customerduepayments.edit', compact('customerduepayment'), $data);
    }

    public function update(Request $request, CustomerDuePayment $customerduepayment)
    {
        //  Gate::authorize('admin.customerduepayment.update');
        $request->validate([
            'customer_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        $customerduepayment->update(array_merge($request->all(), [
            'date'     => $request->date ? $request->date : Date('Y-m-d'),
            'year'     => Date('Y'),
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Customer Due Payment successfully Updated'];
        return to_route('admin.customerduepayment.index')->withNotify($notify);
    }

    public function destroy(CustomerDuePayment $customerduepayment)
    {
        Gate::authorize('admin.customerduepayment.destroy');
        $customerduepayment->delete();
        $notify[] = ['success', "Customer Due Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
