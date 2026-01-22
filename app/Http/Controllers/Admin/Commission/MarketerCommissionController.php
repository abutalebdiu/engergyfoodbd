<?php

namespace App\Http\Controllers\Admin\Commission;

use PDF;
use App\Models\User;
use App\Models\HR\Marketer;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Http\Controllers\Controller;
use App\Models\Account\CustomerDuePayment;
use Rakibhstu\Banglanumber\NumberToBangla;
use App\Models\Commission\CommissionInvoice;
use App\Models\Commission\MarketerCommission;
use App\Models\Commission\MarketerCommissionPayment;

class MarketerCommissionController extends Controller
{

    public function index(Request $request)
    {
        $data['marketers'] = Marketer::get();
        $data['months']  = Month::get();


        $query = MarketerCommission::query();

        if ($request->has(['start_date', 'end_date'])) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $query->whereBetween('date', [$start_date, $end_date]);
        }

        if ($request->has('marketer_id')) {
            $data['marketer_id'] = $request->marketer_id;
            $query->where('marketer_id', $request->marketer_id);
        }


        $data['invoices'] = $query->get();



        if ($request->has('search'))
        {
            return view('admin.commissions.marketercommissions.index', $data);
        }
        elseif ($request->has('pdf'))
        {
            $pdf =  PDF::loadView('admin.commissions.marketercommissions.index_pdf', $data);
            return $pdf->stream('marketer_commission_list.pdf');
        }
        else
        {
            return view('admin.commissions.marketercommissions.index', $data);
        }
    }

    public function create(Request $request)
    {
        $data['marketers'] = Marketer::get();

        $query = Order::query();


        if ($request->marketer_id) {
            $data['marketer_id']        = $request->marketer_id;
            $query->where('marketer_id', $request->marketer_id);
            $data['findmarketer']       = Marketer::find($request->marketer_id);
            $data['searching']          = "Yes";
        } else {
            $data['searching']          = "No";
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date']         = $request->start_date;
            $data['end_date']           = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data['orders'] = $query->orderBy('date', 'asc')
                                ->where('mc_invoice_id', null)
                                ->get();



        if ($request->marketer_id) {
            $uniqueCustomerIds = $query->distinct('customer_id')->pluck('customer_id');
            $data['customerduepayment'] = CustomerDuePayment::whereIn('customer_id', $uniqueCustomerIds)
                            ->whereBetween('date', [$request->start_date, $request->end_date])
                            ->where('status', 'paid')
                            ->sum('amount');

              $users = User::where('reference_id',$request->marketer_id)->get();

              $previousdue = 0;

              foreach($users as $user)
              {

                  $countinvoice = CommissionInvoice::where('customer_id',$user->id)->whereNotIn('month_id',[Date('m')-1])->count();

                  if($countinvoice>0)
                  {
                       $previousdue  += CommissionInvoice::where('customer_id',$user->id)->orderBy('month_id','desc')->whereNotIn('month_id',[Date('m')-1])->first()->amount;
                  }
                  else{
                      // $previousdue  += User::where('reference_id',$request->marketer_id)->sum('opening_due');
                  }

              }



            $data['previousdues'] = $previousdue;
        }


        return view('admin.commissions.marketercommissions.create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'order_id'        => 'required|array',
            'order_id.*'      => 'required|exists:orders,id',
            'payable_amount'  => 'required',
        ]);


        try {

            $invoice = new MarketerCommission();
            $invoice->marketer_id           = $request->marketer_id;
            $invoice->date                  = now()->format('Y-m-d');
            $invoice->previous_due          = $request->previous_due;
            $invoice->net_amount            = $request->net_amount;
            $invoice->paid_amount           = $request->paid_amount;
            $invoice->customer_due_payment  = $request->customer_due_payment;
            $invoice->total_due_amount      = $request->total_due_amount;
            $invoice->payable_amount        = $request->payable_amount;
            $invoice->amount                = $request->payable_amount;
            $invoice->payment_status        = 'Unpaid';
            $invoice->save();

            $invoice->invoice_no            = "MC00" . $invoice->id;
            $invoice->entry_id = auth('admin')->user()->id;
            $invoice->save();


            foreach ($request->order_id as $key => $item) {
                $order = Order::find($item);
                $order->update(['mc_invoice_id' => $invoice->id]);
            }

            $notify[] = ['success', "Commission Invoice created successfully"];
            return to_route('admin.marketercommission.index')->withNotify($notify);
        } catch (Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function show(MarketerCommission $marketercommission)
    {
        $data['marketercommissionpayments'] = MarketerCommissionPayment::latest()->where('marketer_commission_id', $marketercommission->id)->get();
        return view('admin.commissions.marketercommissions.show', compact('marketercommission'), $data);
    }

    public function edit(MarketerCommission $marketercommission)
    {
        return view('admin.commissions.commissioninvoices.edit', compact('marketercommission'));
    }

    public function update(Request $request, MarketerCommission $marketercommission)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $marketercommission->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'MarketerCommission successfully Updated'];
        return to_route('admin.MarketerCommission.index')->withNotify($notify);
    }

    public function destroy(MarketerCommission $marketercommission)
    {
        Order::where('mc_invoice_id', $marketercommission->id)->update(['mc_invoice_id' => null]);

        $marketercommission->delete();
        $notify[] = ['success', "MarketerCommission deleted successfully"];
        return back()->withNotify($notify);
    }

    public function printinvoice($id)
    {
        $marketercommission = MarketerCommission::find($id);
        $data['marketercommissionpayments'] = MarketerCommissionPayment::latest()->where('marketer_commission_id', $marketercommission->id)->get();
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($marketercommission->payable_amount);

        $pdf = PDF::loadView('admin.commissions.marketercommissions.invoice', $data, compact('marketercommission'));
        return $pdf->stream('invoice.pdf');

        // return view('admin.commissions.marketercommissions.invoice',$data);
    }
}
