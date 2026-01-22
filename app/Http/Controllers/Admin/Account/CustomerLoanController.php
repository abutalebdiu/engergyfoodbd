<?php

namespace App\Http\Controllers\Admin\Account;

use PDF;
use Carbon\Carbon;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\CustomerLoan;
use App\Models\Account\TransactionHistory;
use App\Models\Account\CustomerLoanPayment;

class CustomerLoanController extends Controller
{

    public function index(Request $request)
    {
        $query = CustomerLoan::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->isCurrentMonth());
        }

        $data['customerloans'] = $query->latest()->paginate(100);


        if ($request->has('search')) {
            return view('admin.accounts.customerloan.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.accounts.customerloan.view_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {
        } else {
            return view('admin.accounts.customerloan.view', $data);
        }
    }

    public function create()
    {
        $data['months']         = Month::get();
        $data['years']          = Year::orderBy('name', 'DESC')->get();
        return view('admin.accounts.customerloan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $customerloan =  CustomerLoan::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));


        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no     = '';
        $transactionhistory->reference_no   = '';
        $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->module_id      = 20; // Official Loan
        $transactionhistory->module_invoice_id = $customerloan->id;
        $transactionhistory->amount         = $request->amount;
        $transactionhistory->cdf_type       = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id     = $request->account_id;
        $transactionhistory->note           = $request->note;
        $transactionhistory->save();

        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();


        $account = Account::find($request->account_id);
        $accountbalance = $account->balance($account->id) - ($request->amount);

        // transaction hoar ager balance
        $transactionhistory->pre_balance = $accountbalance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        // Account Balance Update
        $account->main_balance = $accountbalance + ($request->amount);
        $account->save();


        // transaction hoar porer balance
        $transactionhistory->per_balance = $accountbalance + ($request->amount);
        $transactionhistory->save();


        $notify[] = ['success', 'Customer Loan successfully Added'];
        return to_route('admin.customerloan.index')->withNotify($notify);
    }

    public function show(CustomerLoan $customerloan)
    {
        $data['customerloanpayments'] = CustomerLoanPayment::latest()->where('customer_loan_id', $customerloan->id)->get();
        return view('admin.accounts.customerloan.show', compact('customerloan'), $data);
    }

    public function edit(CustomerLoan $customerloan)
    {
        $data['months']         = Month::get();
        $data['years']          = Year::orderBy('name', 'DESC')->get();
        return view('admin.accounts.customerloan.edit', compact('customerloan'), $data);
    }

    public function update(Request $request, CustomerLoan $customerloan)
    {
        $request->validate([
            'title'             => 'required',
            'amount'            => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required',
        ]);

        $customerloan->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Customer Loan successfully Updated'];
        return to_route('admin.customerloan.index')->withNotify($notify);
    }

    public function destroy(CustomerLoan $customerloan)
    {
        CustomerLoanPayment::where('customer_loan_id', $customerloan->id)->delete();

        $customerloan->delete();

        $notify[] = ['success', "Customer Loan deleted successfully"];
        return back()->withNotify($notify);
    }
}
