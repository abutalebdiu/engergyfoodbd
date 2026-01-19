<?php

namespace App\Http\Controllers\Admin\Account;

use Carbon\Carbon;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\OfficialLoan;
use App\Models\Account\TransactionHistory;
use App\Models\Account\OfficialLoanPayment;
use PDF;

class OfficialLoanController extends Controller
{

    public function index(Request $request)
    {
        $query = OfficialLoan::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->isCurrentMonth());
        }

        $data['officialloans'] = $query->latest()->paginate(100);


        if ($request->has('search')) {
            return view('admin.accounts.officialloans.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.accounts.officialloans.view_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {
        } else {
            return view('admin.accounts.officialloans.view', $data);
        }
    }

    public function create()
    {
        $data['months']         = Month::get();
        $data['years']          = Year::orderBy('name', 'DESC')->get();
        return view('admin.accounts.officialloans.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $officialloan =  OfficialLoan::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));


        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no     = '';
        $transactionhistory->reference_no   = '';
        $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->module_id      = 19; // Official Loan
        $transactionhistory->module_invoice_id = $officialloan->id;
        $transactionhistory->amount         = $request->amount;
        $transactionhistory->cdf_type       = 'credit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id     = $request->account_id;
        $transactionhistory->note           = $request->note;
        $transactionhistory->save();

        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
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


        $notify[] = ['success', 'Official Loan successfully Added'];
        return to_route('admin.officialloan.index')->withNotify($notify);
    }

    public function show(OfficialLoan $officialloan)
    {
        $data['officialloanpayments'] = OfficialLoanPayment::latest()->where('official_loan_id', $officialloan->id)->get();
        return view('admin.accounts.officialloans.show', compact('officialloan'), $data);
    }

    public function edit(OfficialLoan $officialloan)
    {
        $data['months']         = Month::get();
        $data['years']          = Year::orderBy('name', 'DESC')->get();
        return view('admin.accounts.officialloans.edit', compact('officialloan'), $data);
    }

    public function update(Request $request, OfficialLoan $officialloan)
    {
        $request->validate([
            'title'             => 'required',
            'amount'            => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required',
        ]);

        $officialloan->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Official Loan successfully Updated'];
        return to_route('admin.officialloan.index')->withNotify($notify);
    }

    public function destroy(OfficialLoan $officialloan)
    {
        OfficialLoanPayment::where('official_loan_id', $officialloan->id)->delete();

        $officialloan->delete();

        $notify[] = ['success', "Official Loan deleted successfully"];
        return back()->withNotify($notify);
    }
}
