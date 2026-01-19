<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\User;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Http\Controllers\Controller;
use App\Models\Service\ServiceInvoice;

class ServiceInvoiceController extends Controller
{

    public function index()
    {
        $data['invoices'] = ServiceInvoice::latest()->get();
        return view('admin.services.invoices.view',$data);
    }

    public function create()
    {
        $data['customers'] = User::where('type','customer')->where('status','1')->where('subscribe','Yes')->get();
        $data['months'] = Month::get();
        $data['years'] = Year::latest()->get();
        return view('admin.services.invoices.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'month_id'      => 'required',
            'year_id'       => 'required',
            'term'          => 'required'
        ]);

        $query = User::query();

        if($request->customer_id)
        {
            $query->where('id',$request->customer_id);
        }

        if($request->term)
        {
            $query->where('term',$request->term);
        }

        $customers = $query->where('status','1')->where('subscribe','Yes')->where('type','customer')->get();

        foreach($customers  as $customer)
        {
            $invoice = New ServiceInvoice();
            $invoice->customer_id            = $customer->id;
            $invoice->month_id               = $request->month_id;
            $invoice->year_id                = $request->year_id;   
            $invoice->amount                 = $customer->amount;         
            $invoice->status                 = 'Generated';
            $invoice->save();

            $invoice->invoice_no = "SI0".$invoice->id;
            $invoice->save();
        }
       
       
        $notify[] = ['success', 'Service Invoice successfully Added'];
        return to_route('admin.serviceinvoice.index')->withNotify($notify);
    }

    public function show(ServiceInvoice $serviceinvoice)
    {
         return view('admin.services.invoices.show',compact('serviceinvoice'));
    }

    public function edit(ServiceInvoice $serviceinvoice)
    {
        $data['customers'] = User::where('type','customer')->where('status','1')->where('subscribe','Yes')->get();
        $data['months'] = Month::get();
        $data['years'] = Year::get();
        return view('admin.services.invoices.edit',compact('serviceinvoice'),$data);
    }

    public function update(Request $request, ServiceInvoice $serviceinvoice)
    {
        $request->validate([
            'month_id'      => 'required',
            'year_id'       => 'required'
        ]);

        $serviceinvoice->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Service Invoice successfully Updated'];
        return to_route('admin.serviceinvoice.index')->withNotify($notify);
    }

    public function destroy(ServiceInvoice $serviceinvoice)
    {
        $serviceinvoice->delete();
        $notify[] = ['success', "Service Invoice deleted successfully"];
        return back()->withNotify($notify);
    }
}
