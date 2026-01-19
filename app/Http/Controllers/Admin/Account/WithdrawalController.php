<?php

namespace App\Http\Controllers\Admin\Account;

use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Account\Withdrawal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;

class WithdrawalController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.withdrawal.list');
        $data['Withdrawals'] = Withdrawal::active()->latest()->get();

        $query = Withdrawal::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->isCurrentMonth());
        }

        $data['Withdrawals'] = $query->latest()->paginate(100);


        if ($request->has('search')) {
            return view('admin.accounts.withdrawals.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.accounts.withdrawals.view_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {
        } else {
            return view('admin.accounts.withdrawals.view', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.withdrawal.create');
        return view('admin.accounts.withdrawals.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.withdrawal.store');
        $request->validate([
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
        ]);

        $withdrawal =  Withdrawal::create(array_merge($request->except('attachment'), [
            'date'      => $request->date ? $request->date : Date('Y-m-d'),
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $withdrawal->tnx_no = "WP00" . $withdrawal->id;
        $withdrawal->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->invoice_no = $withdrawal->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 8; // Withdrawal Payment
        $transactionhistory->module_invoice_id = $withdrawal->id;
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


        $notify[] = ['success', 'Withdrawal successfully Added'];
        return to_route('admin.withdrawal.index')->withNotify($notify);
    }

    public function show(Withdrawal $withdrawal)
    {
        Gate::authorize('admin.withdrawal.show');
        return view('admin.accounts.withdrawals.show', compact('withdrawal'));
    }

    public function edit(Withdrawal $withdrawal)
    {
        Gate::authorize('admin.withdrawal.edit');
        return view('admin.accounts.withdrawals.edit', compact('withdrawal'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        Gate::authorize('admin.withdrawal.update');

        $request->validate([
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
        ]);

        $withdrawal->update(array_merge($request->except('attachment'), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Withdrawal successfully Updated'];
        return to_route('admin.withdrawal.index')->withNotify($notify);
    }

    public function destroy(Withdrawal $withdrawal)
    {
        Gate::authorize('admin.withdrawal.destroy');

        $withdrawal->delete();
        $notify[] = ['success', "Withdrawal deleted successfully"];
        return back()->withNotify($notify);
    }
}
