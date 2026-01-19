<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\TransactionHistory;
use App\Models\Service\ServiceInvoice;
use App\Models\Service\ServiceInvoicePayment;
 

class ServiceInvoicePaymentController extends Controller
{

    public function index()
    {
        $data['payments'] = ServiceInvoicePayment::latest()->get();
        return view('admin.services.payments.view',$data);
    }

    public function create()
    {
        $data['customers'] = User::where('type','customer')->where('status','1')->where('subscribe','Yes')->get();
        return view('admin.services.payments.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'                => 'required',
            'customer_id'           => 'required',
            'service_invoice_id'    => 'required',
            'payment_method_id'     => 'required',
            'account_id'            => 'required',
        ]); 

       $servicepayment = ServiceInvoicePayment::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Paid'
        ]));

        $servicepayment->tnx_no = 'SP000' . $servicepayment->id;
        $servicepayment->save();

        $sumpayment = ServiceInvoice::where('id', $servicepayment->service_invoice_id)->where('customer_id', $request->customer_id)->sum('amount');

        $findserviceinvoice = ServiceInvoice::find($request->service_invoice_id);
        if ($sumpayment == $findserviceinvoice->totalamount) {
            $findserviceinvoice->status = "Paid";
            $findserviceinvoice->save();
        } else {
            $findserviceinvoice->status = "Partial";
            $findserviceinvoice->save();
        }


        $transactionhistory = new TransactionHistory();
        $transactionhistory->invoice_no = $servicepayment->tnx_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id = 11; // Service Payment
        $transactionhistory->module_invoice_id = $servicepayment->id;
        $transactionhistory->amount = $request->amount;
        $transactionhistory->cdf_type = 'credit';
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

        $account->main_balance = $account->main_balance + $request->amount;
        $account->save();

         // transaction hoar porer balance
         $transactionhistory->per_balance = $account->main_balance;
         $transactionhistory->save();


        $notify[] = ['success', 'Service Invoice Payment successfully Added'];
        return to_route('admin.serviceinvoicepayment.index')->withNotify($notify);
    }

    public function show(ServiceInvoicePayment $serviceInvoicePayment)
    {
         return view('admin.ServiceInvoicePayment.show',compact('serviceInvoicePayment'));
    }

    public function edit(ServiceInvoicePayment $serviceInvoicePayment)
    {
        return view('admin.ServiceInvoicePayment.edit',compact('serviceInvoicePayment'));
    }

    public function update(Request $request, ServiceInvoicePayment $serviceInvoicePayment)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $serviceInvoicePayment->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'ServiceInvoicePayment successfully Updated'];
        return to_route('admin.ServiceInvoicePayment.index')->withNotify($notify);
    }

    public function destroy(ServiceInvoicePayment $serviceinvoicepayment)
    {
        $serviceinvoicepayment->delete();
        $notify[] = ['success', "Service Invoice Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
