<?php

namespace App\Http\Controllers\Admin\Order;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HR\Employee;
use App\Models\HR\Marketer;
use App\Models\Order\Order;
use App\Exports\OrderExport;
use App\Exports\OrderDemandReport;

use Illuminate\Http\Request;
use App\Models\Order\Quotation;
use App\Models\Product\Product;
use NumberToWords\NumberToWords;
use App\Models\Order\OrderDetail;
use App\Models\Order\OrderReturn;
use App\Http\Controllers\Controller;
use App\Models\Account\OrderPayment;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Rakibhstu\Banglanumber\NumberToBangla;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.order.list');

        $data['customers'] = User::where('type', 'customer')->orderBy('uid', 'ASC')->get(['id','uid','name']);

        $data['marketers'] = Marketer::where('status', 'Active')->get();

        $query = Order::query();

        if ($request->customer_id) {
            $data['customer_id']        = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->marketer_id) {
            $data['marketer_id']        = $request->marketer_id;
            $query->where('marketer_id', $request->marketer_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(40));
        }

        $data['orders'] = $query->latest()->paginate(100);

        

        if($request->ajax()){
            return response()->json([
                "status" => true,
                "message" => "Data view",
                "html"=> view('admin.orders.orders.inc.order_table', $data)->render(),
            ]);
        }

        if ($request->has('search')) {
            return view('admin.orders.orders.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.orders.order_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new OrderExport($data), 'Order_list.xlsx');
        }

        elseif ($request->has('invoice')) {
            $pdf =  PDF::loadView('admin.orders.orders.all_orders_invoice', $data);
            return $pdf->stream('invoice_list.pdf');

           // return view('admin.orders.orders.all_quotation_invoice', $data);
        }
        elseif ($request->has('challan')) {
            $pdf =  PDF::loadView('admin.orders.orders.all_orders_challan', $data);
            return $pdf->stream('challan_list.pdf');
        }

        else {
            return view('admin.orders.orders.view', $data);
        }


        // add paginate to large data handle by query

        return view('admin.orders.orders.view', $data);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.order.create');
        if ($request->quotation_id) {
            $data['quotation'] = Quotation::find($request->quotation_id);
        } else {
            $data['quotation'] = null;
        }
        return view('admin.orders.orders.create', $data);
    }

    public function store(Request $request)
    {
        //Gate::authorize('admin.order.store');

        // return $request->all();
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'customer_id' => 'required|exists:users,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        // foreach ($request->product_id as $key => $value) {
        //     $product = Product::find($value);
        //     if ($request->qty[$key] > $product->qty) {
        //         $notify[] = ['error', "Quantity is not available"];
        //         return back()->withNotify($notify);
        //     }
        // }

        DB::beginTransaction();
        try {
            $order = Order::create([
                "date"          => $request->date ? $request->date : date('Y-m-d'),
                "quotation_id"  => $request->quotation_id,
                "customer_id"   => $request->customer_id,
                "reference_id"  => $request->reference_id,
                "sub_total"     => $request->sub_total,
                "discount_amount" => $request->discount_amount,
                "totalamount"   => $request->grand_total,
                "net_amount"    => $request->grand_total,
                "payment_status" => "Unpaid",
                "status"        => "Active",
                "entry_id"      => auth('admin')->user()->id
            ]);

            if($request->oid)
            {
                 $order->oid = $request->oid;
            }
            else{
                 $order->oid = "OID000" . $order->id;
            }

            $order->save();
            
            
            if($request->quotation_id)
            {
                $quotation = Quotation::find($request->quotation_id);
                $quotation->order_id = $order->id;
                $quotation->save();
            }
        
            

            $ref_commission = 0;

            foreach ($request->product_id as $key => $value) {

                // $product = Product::find($value);
                // $product->qty = $product->qty - $request->qty[$key];
                // $product->save();

                $product_commission = 0;
                for ($i = 0; $i < $request->qty[$key]; $i++) {
                    if ($request->reference_id) {
                        $product_commission += calculateCommission($request->product_id[$key], $request->customer_id, $request->price[$key]);
                    }
                }

                $ref_commission += $product_commission;

                $order->orderdetail()->create([
                    "product_id"        => $request->product_id[$key],
                    "purchase_price"    => $request->purchase_price[$key],
                    "purchase_total"    => $request->purchase_price[$key] * $request->qty[$key],
                    "qty"               => $request->qty[$key],
                    "price"             => $request->price[$key],
                    "amount"            => floor($request->price[$key] * $request->qty[$key]),
                    "product_commission" => $product_commission
                ]);
            }

            $order->return_purchase_amount  = 0;
            $order->return_amount           = 0;
            $order->paid_amount             = 0;
            $order->purchase_amount         = $order->orderdetail->sum('purchase_total');
            $order->profit                  = $order->net_amount - $order->orderdetail->sum('purchase_total');
            $order->save();


            if ($request->customer_id) {
                storeCommission($order, $ref_commission, 'commission_amount');
            }

            // session clear
            session()->forget('order_products');

            // customer session clear
            session()->forget('customer');

            DB::commit();

            $notify[] = ['success', "Order created successfully"];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
             DB::rollBack();
            $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function show(Order $order)
    {

       // Gate::authorize('admin.order.show');

        $data['type'] = $order->type;
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($order->customer_due);

        return view('admin.orders.orders.show', compact('order'), $data);
    }

    public function edit(Order $order)
    {
      //  Gate::authorize('admin.order.edit');
        if (!$order) {
            $notify[] = ['error', "An error occurred while processing your request."];
            return back()->withNotify($notify);
        }

        return view('admin.orders.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
      //  Gate::authorize('admin.order.update');
        $request->validate([
            'oid' => 'required',
            'customer_id' => 'required',
            'date' => 'required',
            'product_id' => 'required|array',
            'purchase_price' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'amount' => 'required|array',
        ]);

        $order->update(array_merge(
            $request->except([
                '_token',
                'product_id',
                'total',
                'grand_total',
                'purchase_price',
                'order_detial_id',
                'amount',
                'qty',
                'price'
            ]),
            [
                "discount_type" => $request->discount_type,
                "discount"    => $request->discount,
                "discount_amount" => $request->discount_amount,

                "vat_type"    => $request->vat_type,
                "vat"         => $request->vat,
                "vat_amount"  => $request->vat_amount,

                "ait_type"    => $request->ait_type,
                "ait"         => $request->ait,
                "ait_amount"  => $request->ait_amount,
                'totalamount' => $request->grand_total,
                "net_amount"    => $request->grand_total,
                'edit_id' => auth('admin')->user()->id,
                'edit_at' => now(),
            ]
        ));



        $order->orderdetail()->delete();

        $input = $request->all();

        $ref_commission = 0;

        foreach ($input['product_id'] as $key => $productId) {

            $product_commission = 0;
            for ($i = 0; $i < $request->qty[$key]; $i++) {
                if ($request->reference_id) {
                    $product_commission += calculateCommission($request->product_id[$key], $request->customer_id, $request->price[$key]);
                }
            }

            $ref_commission += $product_commission;


            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $input['product_id'][$key],
                'purchase_price' => $input['purchase_price'][$key],
                "purchase_total"    => $input['purchase_price'][$key] * $input['qty'][$key],
                'qty' => $input['qty'][$key],
                'price' => $input['price'][$key],
                'amount' => $input['amount'][$key],
                'product_commission' => $product_commission,
                'edit_id' => auth('admin')->user()->id
            ]);
        }


        if ($request->customer_id) {
            storeCommission($order, $ref_commission, 'commission_amount');
        }

        $order->purchase_amount         = $order->orderdetail->sum('purchase_total');
        $order->profit                  = $order->net_amount - $order->orderdetail->sum('purchase_total');
        $order->save();


        // session clear
        session()->forget('order_products');

        // customer session clear
        session()->forget('customer');

        $notify[] = ['success', 'Order successfully Updated'];
        return to_route('admin.order.index', ['type' => $order->type])->withNotify($notify);
    }

    public function destroy(Order $order)
    {
        Gate::authorize('admin.order.destroy');

        DB::beginTransaction();
        try {

            OrderDetail::where('order_id', $order->id)->delete();
            OrderPayment::where('order_id', $order->id)->delete();
            OrderReturn::where('order_id', $order->id)->delete();
            
              
            if($order->quotation_id)
            {
                $quotation = Quotation::find($order->quotation_id);
                $quotation->order_id = null;
                $quotation->save();
            }
            
            
            $order->delete();

            DB::commit();

            $notify[] = ['success', "Order deleted successfully"];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }


    public function printinvoice($id)
    {
        Gate::authorize('admin.order.printinvoice');

        $data['order'] = Order::find($id);
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($data['order']->customer_due);
        $pdf = PDF::loadView('admin.orders.orders.invoice', $data);
        return $pdf->stream('invoice.pdf');

        // return view('admin.orders.orders.invoice',$data);
    }

    public function printchallan($id)
    {
        Gate::authorize('admin.order.printchallan');
        $data['order'] = Order::find($id);
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($data['order']->customer_due);

        $pdf = PDF::loadView('admin.orders.orders.challan', $data);
        return $pdf->stream('challan.pdf');

        // return view('admin.orders.orders.invoice',$data);
    }

    public function productorder(Request $request)
    {
        Gate::authorize('admin.order.productorder');

        // Check if date is provided
        $data['start_date']     = $request->start_date;
        $data['end_date']       = $request->end_date;
        $data['format']         = $request->format;
        $data['orientation']    = $request->orientation;
        $data['type']           = $request->type ?? "WC";
        $data['searching'] = "Yes";

        // Get customers and their ordered products
        $data['customers'] = User::select('users.id', 'users.name')
            ->join('orders', 'users.id', '=', 'orders.customer_id')
            ->whereBetween('orders.date', [$request->start_date, $request->end_date])
            ->groupBy('users.id', 'users.name')
            ->where('type', 'customer')
            ->get();

        // Get products and customer-wise ordered quantities
        $data['products'] = Product::select('products.id', 'products.name', 'products.department_id', 'departments.name as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'products.department_id')
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('SUM(order_details.qty) as total_qty, SUM(order_details.amount) as total_amount, products.id as product_id, orders.customer_id, products.department_id, departments.name as department_name')
            ->whereBetween('orders.date', [$request->start_date, $request->end_date])
            ->groupBy('products.id', 'products.name', 'orders.customer_id', 'products.department_id', 'departments.name')
            ->get()
            ->groupBy('department_id');



        if ($request->ajax()) {
           return response()->json([
                "status" => true,
                "message" => "Data show successfully!",
                "html" => view('admin.orders.orders.inc.productdemand_table', $data)->render(),
           ], 200);
        }

        if ($request->has('search')) {
            return view('admin.orders.orders.productdemand', $data);
        } elseif ($request->has('pdf')) {
            ini_set("memory_limit", "850M");
            set_time_limit('1000');
            ini_set('pcre.backtrack_limit', 10000000);

            $pdf = PDF::loadView('admin.orders.orders.productdemand_pdf', $data, [], [
                'format' => $request->format, // or 'A3', 'Letter', etc.
                'orientation' => $request->orientation // 'P' for Portrait, 'L' for Landscape
            ]);

            return $pdf->download('productdemand.pdf');

        } elseif ($request->has('excel')) {
            return Excel::download(new OrderDemandReport($data), 'Order_Product_demand_list.xlsx');
        } else {
            return view('admin.orders.orders.productdemand', $data);
        }
    }
    
    
    public function datewisecustomerorder(Request $request)
    {
        $data['start_date']  = $request->start_date;
        $data['end_date']    = $request->end_date;
        $data['customer_id'] = $request->customer_id;

        // Default তারিখ - Current Month Start to Today
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date   = $request->end_date ?? date('Y-m-d');

        // Date range বানানো
        $period = new \DatePeriod(
            new \DateTime($start_date),
            new \DateInterval('P1D'),
            (new \DateTime($end_date))->modify('+1 day')
        );
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        $data['dates'] = $dates;

        // Order query
        $orders = Order::select('customer_id', 'date', \DB::raw('COUNT(*) as total'))
            ->whereBetween('date', [$start_date, $end_date])
            ->when($request->customer_id, function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            })
            ->groupBy('customer_id', 'date')
            ->get();

        // শুধু যাদের order আছে সেইসব customer ID
        $customerIds = $orders->pluck('customer_id')->unique();

        // Customer list (only with orders)
        $data['customers'] = User::whereIn('id', $customerIds)
            ->where('type', 'customer')
            ->orderBy('uid', 'ASC')
            ->get(['id', 'uid', 'name']);
            
        $data['customerss'] = User::where('type', 'customer')
                                ->orderBy('uid', 'ASC')
                                ->get(['id', 'uid', 'name']);

        // Orders কে lookup এ সাজানো [customer_id][date] => total
        $orderData = [];
        foreach ($orders as $order) {
            $orderData[$order->customer_id][$order->date] = $order->total;
        }
        $data['orderData'] = $orderData;


        if ($request->ajax()) {
            return response()->json([
                "status" => true,
                "message" => "Data show successfully!",
                "html" => view('admin.orders.orders.inc.datewisecustomerorder_table', $data)->render(),
            ], 200);
        }

        if ($request->has('search')) {
            return view('admin.orders.orders.datewise_customer_order', $data);
        } elseif ($request->has('pdf')) {
            ini_set("memory_limit", "850M");
            set_time_limit('1000');
            ini_set('pcre.backtrack_limit', 10000000);

            $pdf = PDF::loadView('admin.orders.orders.datewise_customer_order_pdf', $data, [], [
                'format' => 'A4', // or 'A3', 'Letter', etc.
                'orientation' => 'Portrait' // 'P' for Portrait, 'L' for Landscape
            ]);

            //return $pdf->download('datewisecustomersorders.pdf');
            return $pdf->stream('datewisecustomersorders.pdf');
        } else {
            return view('admin.orders.orders.datewise_customer_order', $data);
        }
    }
    
    
}
