<?php

namespace App\Http\Controllers\Admin\Account;


use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\SupplierDuePayment;
use App\Models\Account\TransactionHistory;
use PDF;
class SupplierDuePaymentController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.supplierduepayment.list');
        $data['suppliers'] = User::where('type', 'supplier')->orderby('name', 'asc')->get(['id', 'name']);
        $query = SupplierDuePayment::query();

        if ($request->supplier_id) {
            $data['supplier_id']        = $request->supplier_id;
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        else{
            $query->whereBetween('date', [Date('Y-m-01'), Date('Y-m-t')]);
        }

        $data['supplierduepayments'] = $query->get();

        if ($request->has('search')) {
            return view('admin.accounts.supplierduepayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.accounts.supplierduepayments.supplier_due_payment_pdf', $data);
            return $pdf->stream('supplier_due_payment.pdf');
        } elseif ($request->has('excel')) {
            // return Excel::download(new OrderExport($data), 'Order_list.xlsx');
        } else {
            return view('admin.accounts.supplierduepayments.view', $data);
        }

        //$data['supplierduepayments'] = SupplierDuePayment::latest()->get();
        //return view('admin.accounts.supplierduepayments.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.supplierduepayment.create');
        $data['suppliers'] = User::where('type', 'supplier')->orderby('name', 'asc')->get(['id', 'name']);
        return view('admin.accounts.supplierduepayments.create', $data);
    }

    public function store(Request $request)
    {
        //  Gate::authorize('admin.supplierduepayment.store');
        $request->validate([
            'supplier_id'   => 'required',
            'account_id'    => 'required',
            'amount'        => 'required',
            'date'          => 'required',
        ]);

        $supplierduepayment = SupplierDuePayment::create(array_merge($request->all(), [
            'date'      => $request->date ? $request->date : Date('Y-m-d'),
            'amount'    => bn2en($request->amount),
            'year'      => Date('Y'),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Paid'
        ]));

        $supplierduepayment->tnx_no = 'SDP000' . $supplierduepayment->id;
        $supplierduepayment->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $supplierduepayment->tnx_no;
        $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 18; // Supplier Due Payment
        $transactionhistory->module_invoice_id = $supplierduepayment->id;
        $transactionhistory->amount = bn2en($request->amount);
        $transactionhistory->cdf_type = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $request->supplier_id;
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


        $notify[] = ['success', 'Supplier Due Payment successfully Added'];
        return to_route('admin.supplierduepayment.index')->withNotify($notify);
    }

    public function show(SupplierDuePayment $supplierduepayment)
    {
        Gate::authorize('admin.supplierduepayment.show');
        return view('admin.accounts.supplierduepayments.show', compact('supplierduepayment'));
    }

    public function edit(SupplierDuePayment $supplierduepayment)
    {
        Gate::authorize('admin.supplierduepayment.edit');
        $data['suppliers'] = User::where('type', 'supplier')->orderby('name', 'asc')->get(['id', 'name']);
        return view('admin.accounts.supplierduepayments.edit', compact('supplierduepayment'), $data);
    }

    public function update(Request $request, SupplierDuePayment $supplierduepayment)
    {
        // Gate::authorize('admin.supplierduepayment.update');
        $request->validate([
            'supplier_id'   => 'required',
            'account_id'    => 'required',
            'amount'        => 'required',
            'date'          => 'required',
        ]);

        $supplierduepayment->update(array_merge($request->all(), [
            'date'     => $request->date ? $request->date : Date('Y-m-d'),
            'year'     => Date('Y'),
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Supplier Due Payment successfully Updated'];
        return to_route('admin.supplierduepayment.index')->withNotify($notify);
    }

    public function destroy(SupplierDuePayment $supplierduepayment)
    {
        Gate::authorize('admin.supplierduepayment.destroy');

        $supplierduepayment->delete();
        $notify[] = ['success', "Supplier Due Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
