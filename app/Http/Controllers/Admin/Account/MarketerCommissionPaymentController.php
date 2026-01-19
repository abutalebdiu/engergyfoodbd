<?php

namespace App\Http\Controllers\Admin\Account;

use PDF;
use Carbon\Carbon;
use App\Models\HR\Marketer;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\TransactionHistory;
use App\Models\Commission\MarketerCommission;
use App\Models\Commission\MarketerCommissionPayment;

class MarketerCommissionPaymentController extends Controller
{

    public function index(Request $request)
    {
        $data['marketers'] = Marketer::get();


        $query = MarketerCommissionPayment::query();

        if ($request->marketer_id) {
            $data['marketer_id'] = $request->marketer_id;
            $query->where('marketer_id', $request->marketer_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subMonth(1));
        }
        $data['marketerCommissionPayments'] =  $query->latest()->paginate(100);


        if ($request->has('search'))
        {
            return view('admin.commissions.marketercommissionpayments.view', $data);
        }
        elseif ($request->has('pdf'))
        {
            $pdf =  PDF::loadView('admin.commissions.marketercommissionpayments.marketer_commission_payment_pdf', $data);
            return $pdf->stream('marketer_commission_payment.pdf');
        }
        else
        {
            return view('admin.commissions.marketercommissionpayments.view', $data);
        }


    }

    public function create()
    {
        return view('admin.MarketerCommissionPayment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'            => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required',
        ]);

        $marketercommissionpayment = MarketerCommissionPayment::create(array_merge($request->all(), [
            'date'      => $request->date ? $request->date : Date('Y-m-d'),
            'year'      => Date('Y'),
            'amount'    => bn2en($request->amount),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Paid'
        ]));
        
        MarketerCommission::where('id',$marketercommissionpayment->marketer_commission_id)->update(['payment_status'=>'Paid']);

        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no     = '';
        $transactionhistory->reference_no   = '';
        $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->module_id      = 22; // Marketer Commission Payment
        $transactionhistory->module_invoice_id = $marketercommissionpayment->id;
        $transactionhistory->amount         = bn2en($request->amount);
        $transactionhistory->cdf_type       = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id     = $request->account_id;
        $transactionhistory->client_id      = $request->marketer_id;
        $transactionhistory->note           = $request->note;
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



        $notify[] = ['success', 'Marketer Commission Payment successfully Added'];
        return redirect()->back()->withNotify($notify);
    }

    public function show(MarketerCommissionPayment $marketerCommissionPayment)
    {
        return view('admin.MarketerCommissionPayment.show', compact('marketerCommissionPayment'));
    }

    public function edit(MarketerCommissionPayment $marketerCommissionPayment)
    {
        return view('admin.MarketerCommissionPayment.edit', compact('marketerCommissionPayment'));
    }

    public function update(Request $request, MarketerCommissionPayment $marketerCommissionPayment)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $marketerCommissionPayment->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'MarketerCommissionPayment successfully Updated'];
        return to_route('admin.MarketerCommissionPayment.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $payment = MarketerCommissionPayment::find($id);
        
        MarketerCommission::where('id',$payment->marketer_commission_id)->update(['payment_status'=>'Unpaid']);

        $payment->delete();

        $notify[] = ['success', "Marketer Commission Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
