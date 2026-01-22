<?php

namespace App\Http\Controllers\Admin\Commission;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Constants\Status;
use App\Models\HR\Marketer;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Traits\ProcessByDate;
use App\Models\Account\Account;
use App\Models\Account\Settlement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\PaymentMethod;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\TransactionHistory;
use App\Models\Commission\CommissionInvoice;
use App\Models\Commission\CommissionInvoicePayment;
use App\Http\Controllers\Admin\Order\ManageCustomerController;

class CommissionInvoiceController extends Controller
{
    use ProcessByDate;

    // public function index(Request $request)
    // {
    //     Gate::authorize('admin.commissioninvoice.list');

    //     $query = CommissionInvoice::query();

    //     if ($request->has(['start_date', 'end_date'])) {
    //         $start_date = $request->input('start_date');
    //         $end_date = $request->input('end_date');

    //         $query->whereBetween('date', [$start_date, $end_date]);
    //     }

    //     if ($request->has('user_id')) {
    //         $query->where('user_id', $request->user_id);
    //     }

    //     if ($request->has('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     $data['invoices'] = $query->latest()->get();
    //     $data['users'] = User::where('status', 1)->whereIn('commission_type',['Monthly','Daily'])->where('type', 'customer')->get();

    //     return view('admin.commissions.commissioninvoices.index', $data);
    // }

    public function index(Request $request)
    {
        Gate::authorize('admin.commissioninvoice.list');

        $query = CommissionInvoice::query()->with('customer');

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->month_id) {
            $query->where('month_id', $request->month_id);
        } else {
            $query->where('month_id', Date('m'));
        }

        if ($request->marketer_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('reference_id', $request->marketer_id);
            });
        }

        $data['commissioninvoices'] = $query->orderby('customer_id', 'asc')
            //->whereBetween('customer_id',[1,50])
            ->get();
        $data['months']  = Month::get();
        $data['marketers'] = Marketer::where('status', 'Active')->get();


        // foreach( $data['commissioninvoices'] as $customerin)
        // {
        //     // $findcustomer = User::find($customerin->customer_id);
        //     // $findcustomer->last_month_due = $customerin->amount;
        //     // $findcustomer->save();

        //     // $findlastmonth = CommissionInvoice::where('month_id',$customerin->month_id-1)->where('customer_id',$customerin->customer_id)->first();

        //     // if($findlastmonth)
        //     // {
        //     //     $customerin->last_month_due = $findlastmonth->amount;
        //     //     $customerin->save();
        //     // }


        // }




        $data['customers'] = User::where('status', 1)->where('type', 'customer')->get();

        if ($request->has('search')) {
            return view('admin.commissions.commissioninvoices.index', $data);
        } elseif ($request->has('invoice')) {
            return view('admin.commissions.commissioninvoices.allinvoice', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.commissions.commissioninvoices.index_pdf', $data);
            return $pdf->stream('commissioninvoice.pdf');
        } else {
            return view('admin.commissions.commissioninvoices.index', $data);

        }
    }


    public function create(Request $request)
    {
        Gate::authorize('admin.commissioninvoice.create');
        $data['customers'] = User::where('status', 1)->whereIn('commission_type', ['Monthly', 'Daily'])->where('type', 'customer')->get();

        $lastmonthdue = 0;

        $query = Order::query();
        $queryforlastrow = Order::query();
        $duepaymentquery = CustomerDuePayment::query();

        if ($request->customer_id) {
            $data['customer_id']        = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
            $queryforlastrow->where('customer_id', $request->customer_id);
            $duepaymentquery->where('customer_id', $request->customer_id);
            $data['findcustomer']       = User::find($request->customer_id);
            $data['searching']          = "Yes";

            $lastcommissioninvoice = CommissionInvoice::where('customer_id', $request->customer_id)->orderBy('id', 'desc')->first();

            if ($lastcommissioninvoice) {
                $lastmonthdue =  $lastcommissioninvoice->amount;
            } else {
                $lastmonthdue =   User::find($request->customer_id)->opening_due;
            }
        } else {
            $data['searching']          = "No";
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date']         = $request->start_date;
            $data['end_date']           = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
            $queryforlastrow->whereBetween('date', [$request->start_date, $request->end_date]);
            $duepaymentquery->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $data['orders'] = $query->orderBy('date', 'asc')
            ->where('commission_invoice_id', 0)
            ->get();

        $data['customerduepayments'] = $duepaymentquery->get();


        // Merge orders and customer due payments
        $mergedData = collect();

        // Add orders to collection
        foreach ($data['orders'] as $order) {
            $mergedData->push([
                'type' => 'order',
                'date' => $order->date,
                'data' => $order
            ]);
        }

        // Add customer due payments to collection
        foreach ($data['customerduepayments'] as $payment) {
            $mergedData->push([
                'type' => 'payment',
                'date' => $payment->date,
                'data' => $payment
            ]);
        }

        // Sort by date
        $data['mergedData'] = $mergedData->sortBy('date')->values();



        $data['lastmonthdueamount'] = $lastmonthdue;

        $lastOrder = $queryforlastrow->orderBy('date', 'desc')->first();

        $data['last_customer_total_due'] = $lastmonthdue + $data['orders']->sum('grand_total') - $data['orders']->sum('paid_amount') -  $data['customerduepayments']->sum('amount');

        if ($request->has('search')) {
            return view('admin.commissions.commissioninvoices.create', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.commissions.commissioninvoices.create_pdf', $data);
            return $pdf->stream('commissioninvoice.pdf');
        } else {
            return view('admin.commissions.commissioninvoices.create', $data);
        }
    }


    // Merge Data 
    // public function create(Request $request)
    // {
    //     Gate::authorize('admin.commissioninvoice.create');
    //     $data['customers'] = User::where('status', 1)->whereIn('commission_type',['Monthly','Daily'])->where('type', 'customer')->get();
    //     $lastmonthdue = 0;
    //     $query = Order::query();
    //     $queryforlastrow = Order::query();
    //     $duepaymentquery = CustomerDuePayment::query();

    //     if ($request->customer_id) {
    //         $data['customer_id']        = $request->customer_id;
    //         $query->where('customer_id', $request->customer_id);
    //         $queryforlastrow->where('customer_id', $request->customer_id);
    //         $duepaymentquery->where('customer_id', $request->customer_id);
    //         $data['findcustomer']       = User::find($request->customer_id);
    //         $data['searching']          = "Yes";

    //         $lastcommissioninvoice = CommissionInvoice::where('customer_id',$request->customer_id)->orderBy('id','desc')->first();

    //         if($lastcommissioninvoice) {
    //             $lastmonthdue =  $lastcommissioninvoice->amount;
    //         } else {
    //             $lastmonthdue =   User::find($request->customer_id)->opening_due;
    //         }

    //     } else {
    //         $data['searching']          = "No";
    //     }

    //     if ($request->start_date && $request->end_date) {
    //         $data['start_date']         = $request->start_date;
    //         $data['end_date']           = $request->end_date;
    //         $query->whereBetween('date', [$request->start_date, $request->end_date]);
    //         $queryforlastrow->whereBetween('date', [$request->start_date, $request->end_date]);
    //         $duepaymentquery->whereBetween('date', [$request->start_date, $request->end_date]);
    //     }

    //     $data['orders'] = $query->orderBy('date', 'asc')
    //                             ->where('commission_invoice_id', 0)
    //                             ->get();
    //     $data['customerduepayments'] = $duepaymentquery->get();

    //     // Merge orders and customer due payments
    //     $mergedData = collect();

    //     // Add orders to collection
    //     foreach ($data['orders'] as $order) {
    //         $mergedData->push([
    //             'type' => 'order',
    //             'date' => $order->date,
    //             'data' => $order
    //         ]);
    //     }

    //     // Add customer due payments to collection
    //     foreach ($data['customerduepayments'] as $payment) {
    //         $mergedData->push([
    //             'type' => 'payment',
    //             'date' => $payment->date,
    //             'data' => $payment
    //         ]);
    //     }

    //     // Sort by date
    //     $data['mergedData'] = $mergedData->sortBy('date')->values();
    //     $data['lastmonthdueamount'] = $lastmonthdue;

    //     $lastOrder = $queryforlastrow->orderBy('date', 'desc')->first();

    //     $data['last_customer_total_due'] = $lastmonthdue + $data['orders']->sum('grand_total') - $data['orders']->sum('paid_amount') - $data['customerduepayments']->sum('amount');

    //     if($request->has('search')) {
    //         return view('admin.commissions.commissioninvoices.create', $data);
    //     } elseif($request->has('pdf')) {
    //         $pdf = PDF::loadView('admin.commissions.commissioninvoices.create_pdf',$data);
    //         return $pdf->stream('commissioninvoice.pdf');
    //     } else {
    //         return view('admin.commissions.commissioninvoices.create', $data);
    //     }
    // }




    // public function store(Request $request)
    // {
    //     Gate::authorize('admin.commissioninvoice.store');

    //     $request->validate([
    //         'start_date' => 'required',
    //         'end_date'   => 'required',
    //     ]);
        
    //     try {

    //         $query = User::query();

    //         if ($request->customer_id) {
    //             $query->where('id', $request->customer_id);
    //         }

    //         $customers = $query->where('type', 'customer')->get();


    //         foreach ($customers as $customer) {
                
    //                     // last month due
    //             $last_month_due = CommissionInvoice::where('month_id', $commissioninvoice->month_id - 1)
    //                     ->where('customer_id', $customer->id)
    //                     ->get();

    //             $orders     = Order::where('customer_id', $customer->id)->whereBetween('date', [$request->start_date, $request->end_date])->where('commission_invoice_id', 0)->get();
    //             $duepayment = CustomerDuePayment::where('customer_id', $customer->id)->whereBetween('date', [$request->start_date, $request->end_date])->sum('amount');

    //             $invoice = new CommissionInvoice();
    //             $invoice->customer_id           = $customer->id;
    //             $invoice->date                  = $request->end_date;
    //             $invoice->month_id              = Date('m', strtotime($request->end_date));
    //             $invoice->year                  = Date('Y', strtotime($request->end_date));
    //             $invoice->order_amount          = $orders->sum('sub_total');
    //             $invoice->return_amount         = $orders->sum('return_amount');
    //             $invoice->net_amount            = $orders->sum('net_amount');
    //             $invoice->paid_amount           = $orders->sum('paid_amount');
    //             $invoice->commission            = $orders->sum('commission');
    //             $invoice->customer_due_payment  = $duepayment;

    //             if ($customer->commission_type == "Monthly") {
    //                 $invoice->commission_amount     = $orders->sum('commission');
    //                 $invoice->receivable_amount     = ($last_month_due + $orders->sum('net_amount') + $duepayment) - ($orders->sum('paid_amount')  - $orders->sum('commission'));
    //                 $invoice->amount                = ($last_month_due + $orders->sum('net_amount') + $duepayment) - ($orders->sum('paid_amount')  - $orders->sum('commission'));
    //                 $invoice->payment_status        = 'Unpaid';
    //             } else {
    //                 $invoice->commission_amount     = 0;
    //                 $invoice->receivable_amount     = ($last_month_due + $orders->sum('net_amount') + $duepayment) - ($orders->sum('paid_amount'));
    //                 $invoice->amount                = ($last_month_due + $orders->sum('net_amount') + $duepayment) - ($orders->sum('paid_amount'));
    //                 $invoice->payment_status        = 'Paid';
    //             }

    //             $invoice->save();


    //             $invoice->invoice_id            = "CI00" . $invoice->id;
    //             $invoice->save();


    //             $findcustomer = User::find($customer->id);
    //             $invoice->last_month_due  = $findcustomer->last_month_due;
    //             $invoice->save();

    //             $findcustomer->last_month_due = $invoice->amount;
    //             $findcustomer->save();


    //             foreach ($orders as $key => $order) {
    //                 $order->update(['commission_invoice_id' => $invoice->id]);
    //             }
    //         }
    //         $notify[] = ['success', "Commission Invoice created successfully"];
    //         return back()->withNotify($notify);
    //     } catch (Exception $e) {
    //         $notify[] = ['error', $e->getMessage()];
    //         return back()->withNotify($notify);
    //     }
    // }

    public function store(Request $request)
    {
    Gate::authorize('admin.commissioninvoice.store');

    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date',
    ]);

    try {

        $invoiceMonth = (int) date('m', strtotime($request->end_date));
        $invoiceYear  = (int) date('Y', strtotime($request->end_date));

        // last month & year calculation
        if ($invoiceMonth == 1) {
            $lastMonth = 12;
            $lastYear  = $invoiceYear - 1;
        } else {
            $lastMonth = $invoiceMonth - 1;
            $lastYear  = $invoiceYear;
        }

        $query = User::where('type', 'customer');

        if ($request->customer_id) {
            $query->where('id', $request->customer_id);
        }

        $customers = $query->get();

        foreach ($customers as $customer) {

            // ✅ last month due (single value)
            $lastMonthInvoice = CommissionInvoice::where('customer_id', $customer->id)
                ->where('month_id', $lastMonth)
                ->where('year', $lastYear)
                ->latest('id')
                ->first();

            $last_month_due = $lastMonthInvoice ? $lastMonthInvoice->amount : 0;

            // orders for selected period
            $orders = Order::where('customer_id', $customer->id)
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->where('commission_invoice_id', 0)
                ->get();

            if ($orders->isEmpty()) {
                continue;
            }

            $duepayment = CustomerDuePayment::where('customer_id', $customer->id)
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->sum('amount');

            $invoice = new CommissionInvoice();
            $invoice->customer_id          = $customer->id;
            $invoice->date                 = $request->end_date;
            $invoice->month_id             = $invoiceMonth;
            $invoice->year                 = $invoiceYear;
            $invoice->order_amount         = $orders->sum('sub_total');
            $invoice->return_amount        = $orders->sum('return_amount');
            $invoice->net_amount           = $orders->sum('net_amount');
            $invoice->paid_amount          = $orders->sum('paid_amount');
            $invoice->commission           = $orders->sum('commission');
            $invoice->customer_due_payment = $duepayment;
            $invoice->last_month_due       = $last_month_due;
            $invoice->entry_id = auth()->guard('admin')->user()->id;

            if ($customer->commission_type === "Monthly") {

                $invoice->commission_amount = $orders->sum('commission');

                $invoice->receivable_amount =
                    ($last_month_due + $orders->sum('net_amount')) - ($orders->sum('paid_amount') + $orders->sum('commission') + $duepayment);

                $invoice->amount         = $invoice->receivable_amount;
                $invoice->payment_status = 'Unpaid';

            } else {

                $invoice->commission_amount = 0;

                $invoice->receivable_amount =
                    ($last_month_due + $orders->sum('net_amount'))  - ($orders->sum('paid_amount') + $duepayment);

                $invoice->amount         = $invoice->receivable_amount;
                $invoice->payment_status = 'Paid';
            }

            $invoice->save();

            $invoice->invoice_id = "CI00" . $invoice->id;
            $invoice->save();

            // update customer last month due
            $customer->last_month_due = $invoice->amount;
            $customer->save();

            // update orders
            Order::whereIn('id', $orders->pluck('id'))
                ->update(['commission_invoice_id' => $invoice->id]);
        }

        return back()->withNotify([['success', 'Commission Invoice created successfully']]);

    } catch (\Exception $e) {
        return back()->withNotify([['error', $e->getMessage()]]);
    }
}



    // old show date: 29-12-2025
    // public function show(CommissionInvoice $commissioninvoice)
    // {
    //     Gate::authorize('admin.commissioninvoice.show');
    //     $data['commissioninvoicepayments'] = CommissionInvoicePayment::latest()->where('invoice_id', $commissioninvoice->id)->get();

    //     $data['last_month_due'] = CommissionInvoice::where('month_id', $commissioninvoice->month_id - 1)->where('customer_id', $commissioninvoice->customer_id)->get();

    //     return view('admin.commissions.commissioninvoices.show', $data, compact('commissioninvoice'));
    // }

    public function show(CommissionInvoice $commissioninvoice)
    {
        Gate::authorize('admin.commissioninvoice.show');

        // এই মাসের orders
        $orders = $commissioninvoice->orders()->with('orderdetail', 'customer', 'orderreturn')->get();

        // এই মাসের customer due payments
        $year = $commissioninvoice->year;
        $month = $commissioninvoice->month_id;
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

        $customerduepayments = CustomerDuePayment::whereBetween('date', [$startDate, $endDate])
            ->where('customer_id', $commissioninvoice->customer_id)
            ->get();

        // orders এবং payments merge করা
        $mergedData = [];

        foreach ($orders as $order) {
            $mergedData[] = [
                'type' => 'order',
                'data' => $order
            ];
        }

        foreach ($customerduepayments as $payment) {
            $mergedData[] = [
                'type' => 'payment',
                'data' => $payment
            ];
        }

        // date-wise sort
        usort($mergedData, function ($a, $b) {
            return strtotime($a['data']->date) <=> strtotime($b['data']->date);
        });

        // last month due
        $last_month_due = CommissionInvoice::where('month_id', $commissioninvoice->month_id - 1)
            ->where('customer_id', $commissioninvoice->customer_id)
            ->get();
         $commissioninvoicepayments = CommissionInvoicePayment::latest()->where('invoice_id', $commissioninvoice->id)->get();
        return view('admin.commissions.commissioninvoices.show', compact('commissioninvoice', 'mergedData', 'last_month_due','commissioninvoicepayments','orders'));
    }




    public function destroy(CommissionInvoice $commissioninvoice)
    {
        Order::where('commission_invoice_id', $commissioninvoice->id)->update(['commission_invoice_id' => 0]);

        $commissioninvoice->delete();
        $notify[] = ['success', "Commission Invoice deleted successfully"];
        return back()->withNotify($notify);
    }


    public function printinvoice($id)
    {
        $commissioninvoice = CommissionInvoice::find($id);
        
        // এই মাসের orders
        $orders = $commissioninvoice->orders()->with('orderdetail', 'customer', 'orderreturn')->get();

        // এই মাসের customer due payments
        $year = $commissioninvoice->year;
        $month = $commissioninvoice->month_id;
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

        $customerduepayments = CustomerDuePayment::whereBetween('date', [$startDate, $endDate])
            ->where('customer_id', $commissioninvoice->customer_id)
            ->get();

        // orders এবং payments merge করা
        $mergedData = [];

        foreach ($orders as $order) {
            $mergedData[] = [
                'type' => 'order',
                'data' => $order
            ];
        }

        foreach ($customerduepayments as $payment) {
            $mergedData[] = [
                'type' => 'payment',
                'data' => $payment
            ];
        }

        // date-wise sort
        usort($mergedData, function ($a, $b) {
            return strtotime($a['data']->date) <=> strtotime($b['data']->date);
        });

        // last month due
        $last_month_due = CommissionInvoice::where('month_id', $commissioninvoice->month_id - 1)
            ->where('customer_id', $commissioninvoice->customer_id)
            ->get();
         $commissioninvoicepayments = CommissionInvoicePayment::latest()->where('invoice_id', $commissioninvoice->id)->get();
         
        $pdf = PDF::loadView('admin.commissions.commissioninvoices.invoice', compact('commissioninvoice', 'mergedData', 'last_month_due','commissioninvoicepayments','orders'));
        return $pdf->stream('invoice.pdf');

        // return view('admin.commissions.marketercommissions.invoice',$data);
    }
}
