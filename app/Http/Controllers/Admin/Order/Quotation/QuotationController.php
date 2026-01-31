<?php

namespace App\Http\Controllers\Admin\Order\Quotation;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order\Quotation;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Order\QuotationDetail;
use Rakibhstu\Banglanumber\NumberToBangla;
use App\Exports\QuotationDemandReport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Distribution\Distribution;
use Illuminate\Support\Facades\Log;

class QuotationController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.quotation.list');

        $data['customers']      = User::where('type', 'customer')->orderBy('uid', 'ASC')->get(['id', 'name', 'uid']);
        $data['distributors']   = Distribution::get();
        $query = Quotation::query()->where('status','Active');

        if ($request->customer_id) {
            $data['customer_id'] = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(40));
        }

        if($request->ajax()){
            $data['orders'] = $query->latest()->paginate(100);
            $data['orders']->withQueryString();
            return response()->json([
                "status" => true,
                "message" => "Data view",
                "render_view"=> view('admin.orders.quotations.inc.quotation_table', $data)->render(),
            ]);
        }

        $data['orders'] = $query->get();
        
        
        
        //   // First, gather all quotations
        // $quotations = Quotation::whereNull('order_id')->whereBetween('date',['2025-11-15','2025-11-23'])->get();
        
        // // Retrieve all relevant orders in one go and index them for quick lookup
        // $orders = Order::whereIn('date', $quotations->pluck('date'))
        //     ->whereIn('customer_id', $quotations->pluck('customer_id'))
        //     ->get()
        //     ->keyBy(function($order) {
        //         return $order->date . '-' . $order->customer_id; // Creates a key based on date and customer_id
        //     });
        
        // foreach ($quotations as $quota) {
        //     // Create the key to lookup the corresponding order
        //     $key = $quota->date . '-' . $quota->customer_id;
        
        //     // Check if order exists for this date and customer_id
        //     if ($orders->has($key)) {
        //         $odata = $orders->get($key);
        
        //         // Update the quotation record
        //         $quota->order_id = $odata->id;
        //         $quota->save();
        
        //         // Ensure $findorder exists before updating it
        //         $findorder = Order::find($quota->order_id);  // Assuming you want to find the order using the quota's order_id
        
        //         if ($findorder) {
        //             // Update the findorder with the quotation_id
        //             $findorder->quotation_id = $odata->id;
        //             $findorder->save();
        //         } else {
        //             // Handle the case where findorder is null, you could either skip or create a new order
        //             // Example: You could create a new order or log the issue
        //             // $findorder = new Order();
        //             // $findorder->quotation_id = $odata->id;
        //             // $findorder->save();
        //             // Alternatively, log an error or skip if you prefer
        //             Log::warning('Order not found for quota: ' . $quota->id);
        //         }
        //     }
        // }

        
        
        if ($request->has('search')) {
            return view('admin.orders.quotations.view', $data);
        }
        elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.quotations.quotation_pdf', $data);
            return $pdf->stream('order_list.pdf');
        }
        elseif ($request->has('invoice')) {
            $pdf =  PDF::loadView('admin.orders.quotations.all_quotation_invoice', $data);
            return $pdf->stream('invoice_list.pdf');

           // return view('admin.orders.quotations.all_quotation_invoice', $data);
        }
        elseif ($request->has('challan')) {
            $pdf =  PDF::loadView('admin.orders.quotations.all_quotation_challan', $data);
            return $pdf->stream('challan_list.pdf');
        }

        else
        {

            return view('admin.orders.quotations.view', $data);
        }
    }
    
    
    public function trash(Request $request)
    {
       // Gate::authorize('admin.quotation.view');
         $customers = User::where('type', 'customer')->orderBy('uid', 'ASC')->get(['id', 'name', 'uid']);
       
        $query = Quotation::query()->where('status','Deleted')->with('customer');

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $quotations = $query->paginate(50);

        return view('admin.orders.quotations.trash_list', compact('quotations','customers'));
    }
      
    
    public function create(Request $request)
    {
        Gate::authorize('admin.quotation.create');

        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        $data['customers'] = User::where('type', 'customer')->orderBy('name', 'asc')->get();
        return view('admin.orders.quotations.create', $data);
    }


    
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|exists:users,id',
            'product_id'    => 'required|array',
            'product_id.*'  => 'exists:products,id',
            'product_qty'   => 'required|array'
        ]);

        $input = $request->all();

        $customer       = User::findOrFail($request->customer_id);
        $distribution   = Distribution::find($customer->distribution_id);
        $previousDue    = $customer->receivable($customer->id);
        $dPreviousDue    = $distribution ? $customer->receivable($customer->id) : 0;

        $products = collect($input['product_id'])
            ->map(function ($pid, $index) use ($input) {
                return [
                    'product_id' => $pid,
                    'qty'        => (int) bn2en($input['product_qty'][$index]),
                ];
            })
            ->filter(fn ($item) => $item['qty'] > 0)
            ->values();

        if ($products->isEmpty()) {
            return $request->ajax()
                ? response()->json([
                    "status" => false,
                    "message" => "Please select products"
                ], 404)
                : back()->withNotify([['error', 'Please select products']]);
        }

        DB::beginTransaction();
        try {

            $order = Quotation::create([
                "date"              => $request->date ?? now()->format('Y-m-d'),
                "customer_id"       => $customer->id,
                "sub_total"         => 0,
                "net_amount"        => 0,
                "commission"        => 0,
                "commission_status" => "Unpaid",
                "status"            => "Active",
                "entry_id"          => auth('admin')->id(),
            ]);

            $order->update([
                'qid' => "OID000" . $order->id
            ]);

            $subTotal      = 0;
            $totalCommission = 0;

            // distributor
            $dc_subTotal      = 0;
            $dc_totalCommission = 0;

            foreach ($products as $item) {

                $product = Product::findOrFail($item['product_id']);

                if (!empty($distribution)) {
                    $customer_price = calculateProductPrice($product->id, $customer->id);

                    $distributor_customer_commissionPerUnit = calculateCommission($product->id, $customer->id);
                    $distributor_customer_productCommission = $distributor_customer_commissionPerUnit * $item['qty'];

                    $amount = floor($customer_price * $item['qty']);

                    $distributor_price = distributorCalculateProductPrice($product->id, $distribution->id);
                    $distributor_commission_per_unit = distributorCalculateCommission($product->id, $distribution->id);
                    $distributor_product_commission = $distributor_commission_per_unit * $item['qty'];
                    $distributor_amount  = floor($distributor_price * $item['qty']);

                    $order->quotationdetail()->create([
                        'product_id'            => $product->id,
                        'qty'                   => $item['qty'],
                        'price'                 => $customer_price,
                        'amount'                => $amount,
                        'product_commission'    => $distributor_product_commission,
                        'dc_price'              => $distributor_price, 
                        'dc_amount'             => $distributor_amount, 
                        'dc_product_commission' => $distributor_customer_productCommission,
                        'entry_id'              => auth('admin')->id(),
                    ]);


                    $subTotal        += $amount;
                    $totalCommission += $distributor_product_commission;

                    $dc_subTotal += $distributor_amount;
                    $dc_totalCommission += $distributor_customer_productCommission;

                } else {
                    $price = calculateProductPrice($product->id, $customer->id);

                    $commissionPerUnit = calculateCommission($product->id, $customer->id);
                    $productCommission = $commissionPerUnit * $item['qty'];

                    $amount = floor($price * $item['qty']);

                    $order->quotationdetail()->create([
                        'product_id'         => $product->id,
                        'qty'                => $item['qty'],
                        'price'              => $price,
                        'amount'             => $amount,
                        'product_commission' => $productCommission,
                        'entry_id'           => auth('admin')->id(),
                    ]);

                    $subTotal        += $amount;
                    $totalCommission += $productCommission;
                }
            }

            // commission logic
            if ($customer->commission_type === "Monthly") {
                $grandTotal = $subTotal;
                $orderDue   = $subTotal;

                // when distributor
                $dGrandTotal = $dc_subTotal;
                $dOrderDue   = $dGrandTotal;

            } else {
                
                $order->update([
                    'commission_status' => "Paid",
                ]);

                $grandTotal = $subTotal - $totalCommission;
                $orderDue   = $grandTotal;

                $dGrandTotal = $dc_subTotal - $dc_totalCommission;
                $dOrderDue   = $dGrandTotal;
            }

            $order->update([
                'sub_total'     => $subTotal,
                'net_amount'    => $subTotal,
                'commission'    => $totalCommission,
                'grand_total'   => $grandTotal,
                'previous_due'  => $previousDue,
                'order_due'     => $orderDue,
                'customer_due'  => $previousDue + $orderDue,
                'distribution_id' => $distribution?->id,
                'dc_sub_total'     => $distribution ? $dc_subTotal : 0,
                'dc_net_amount'    => $distribution ? $dc_subTotal : 0,
                'dc_commission'    => $distribution ? $dc_totalCommission : 0,
                'dc_grand_total'   => $dGrandTotal,
                'dc_previous_due'  => $dPreviousDue,
                'dc_order_due'     => $dOrderDue,
                'dc_customer_due'  => $dPreviousDue + $dOrderDue,
            ]);


            DB::commit();

            return $request->ajax()
                ? response()->json([
                    "status" => true,
                    "redirect" => route('admin.quotation.show', $order->id),
                    "message" => "Quotation created successfully!"
                ], 201)
                : to_route('admin.quotation.show', $order->id)
                    ->withNotify([['success', 'Quotation created successfully']]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::info($e->getMessage());


            return $request->ajax()
                ? response()->json([
                    "status" => false,
                    "message" => "An error occurred while processing your request."
                ], 500)
                : back()->withNotify([['error', $e->getMessage()]]);
        }
    }


    public function show(Quotation $quotation)
    {
      //  Gate::authorize('admin.quotation.show');
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($quotation->customer_due);
        $data['dc_banglanumber'] = $numto->bnWord($quotation->dc_customer_due);
        return view('admin.orders.quotations.show', compact('quotation'), $data);
    }

    public function edit($id)
    {
      //  Gate::authorize('admin.quotation.edit');
        $data['customers'] = User::where('type', 'customer')->orderBy('uid', 'ASC')->get(['id', 'name', 'uid']);
        $data['quotation'] = Quotation::find($id);
        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        return view('admin.orders.quotations.edit', $data);
    }

    
    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'customer_id'  => 'required|exists:users,id',
            'product_id'   => 'required|array',
            'product_id.*' => 'exists:products,id',
            'product_qty'  => 'required|array'
        ]);

        $customer = User::findOrFail($request->customer_id);
        $distribution   = Distribution::find($customer->distribution_id);
        $previousDue = $customer->receivable($customer->id);
        $dPreviousDue    = $distribution ? $customer->receivable($customer->id) : 0;

        // filter products with qty > 0
        $products = collect($request->product_id)
            ->map(function ($pid, $index) use ($request) {
                return [
                    'product_id' => $pid,
                    'qty'        => (int) bn2en($request->product_qty[$index]),
                ];
            })
            ->filter(fn ($item) => $item['qty'] > 0)
            ->values();

        if ($products->isEmpty()) {
            return $request->ajax()
                ? response()->json([
                    "status" => false,
                    "message" => "Please select products"
                ], 404)
                : back()->withNotify([['error', 'Please select products']]);
        }

        DB::beginTransaction();
        try {

            // remove old details
            $quotation->quotationdetail()->delete();

            $subTotal        = 0;
            $totalCommission = 0;

            // distributor
            $dc_subTotal      = 0;
            $dc_totalCommission = 0;

            foreach ($products as $item) {

                $product = Product::findOrFail($item['product_id']);

                if (!empty($distribution)) {
                    $customer_price = calculateProductPrice($product->id, $customer->id);

                    $distributor_customer_commissionPerUnit = calculateCommission($product->id, $customer->id);
                    $distributor_customer_productCommission = $distributor_customer_commissionPerUnit * $item['qty'];

                    $amount = floor($customer_price * $item['qty']);

                    $distributor_price = distributorCalculateProductPrice($product->id, $distribution->id);
                    $distributor_commission_per_unit = distributorCalculateCommission($product->id, $distribution->id);
                    $distributor_product_commission = $distributor_commission_per_unit * $item['qty'];
                    $distributor_amount  = floor($distributor_price * $item['qty']);

                    $quotation->quotationdetail()->create([
                        'product_id'            => $product->id,
                        'qty'                   => $item['qty'],
                        'price'                 => $customer_price,
                        'amount'                => $amount,
                        'product_commission'    => $distributor_product_commission,
                        'dc_price'              => $distributor_price,
                        'dc_amount'             => $distributor_amount,
                        'dc_product_commission' => $distributor_customer_productCommission,
                        'entry_id'              => auth('admin')->id(),
                    ]);


                    $subTotal        += $amount;
                    $totalCommission += $distributor_product_commission;

                    $dc_subTotal += $distributor_amount;
                    $dc_totalCommission += $distributor_customer_productCommission;

                } else {

                    $price = calculateProductPrice($product->id, $customer->id);

                    $commissionPerUnit = calculateCommission($product->id, $customer->id);
                    $productCommission = $commissionPerUnit * $item['qty'];

                    $amount = floor($price * $item['qty']);

                    $quotation->quotationdetail()->create([
                        'product_id'         => $product->id,
                        'qty'                => $item['qty'],
                        'price'              => $price,
                        'amount'             => $amount,
                        'product_commission' => $productCommission,
                        'entry_id'           => auth('admin')->id(),
                    ]);

                    $subTotal        += $amount;
                    $totalCommission += $productCommission;
                }
            }

            // commission logic
            if ($customer->commission_type === "Monthly") {
                $grandTotal = $subTotal;
                $orderDue   = $subTotal;

                // when distributor
                $dGrandTotal = $dc_subTotal;
                $dOrderDue   = $dGrandTotal;
            } else {

                $quotation->update([
                    'commission_status' => "Paid",
                ]);

                $grandTotal = $subTotal - $totalCommission;
                $orderDue   = $grandTotal;

                $dGrandTotal = $dc_subTotal - $dc_totalCommission;
                $dOrderDue   = $dGrandTotal;
            }

            $quotation->update([
                'sub_total'     => $subTotal,
                'net_amount'    => $subTotal,
                'commission'    => $totalCommission,
                'grand_total'   => $grandTotal,
                'previous_due'  => $previousDue,
                'order_due'     => $orderDue,
                'customer_due'  => $previousDue + $orderDue,
                'distribution_id' => $distribution?->id,
                'dc_sub_total'     => $distribution ? $dc_subTotal : 0,
                'dc_net_amount'    => $distribution ? $dc_subTotal : 0,
                'dc_commission'    => $distribution ? $dc_totalCommission : 0,
                'dc_grand_total'   => $dGrandTotal,
                'dc_previous_due'  => $dPreviousDue,
                'dc_order_due'     => $dOrderDue,
                'dc_customer_due'  => $dPreviousDue + $dOrderDue,
            ]);

            DB::commit();

            return $request->ajax()
                ? response()->json([
                    "status" => true,
                    "redirect" => route('admin.quotation.show', $quotation->id),
                    "message" => "Quotation updated successfully!"
                ], 200)
                : to_route('admin.quotation.show', $quotation->id)
                    ->withNotify([['success', 'Quotation updated successfully']]);

        } catch (\Exception $e) {

            DB::rollBack();

            return $request->ajax()
                ? response()->json([
                    "status" => false,
                    "message" => "An error occurred while processing your request."
                ], 500)
                : back()->withNotify([['error', $e->getMessage()]]);
        }
    }



    public function destroy(Quotation $quotation)
    {
        Gate::authorize('admin.quotation.destroy');

        $quotation->status = 'Deleted';
        $quotation->deleted_id = auth('admin')->id();
        $quotation->deleted_at = now();
        $quotation->save();

        $notify[] = ['success', "Order deleted successfully"];
        return back()->withNotify($notify);
    }



    public function restore($id)
    {
        $quotation = Quotation::findOrFail($id);
        //  $quotation->deleted_id = null;
        $quotation->deleted_at = null;
        $quotation->restored_id = auth('admin')->id();
        $quotation->deleted_at = now();
        $quotation->status = 'Active';
        $quotation->save();

        $notify[] = ['success', 'Quotation restored successfully'];
        return back()->withNotify($notify);
    }

    public function forceDelete($id)
    {
        $quotation = Quotation::findOrFail($id);
        QuotationDetail::where('quotation_id',$id)->delete();
        $quotation->delete();

        $notify[] = ['success', 'Quotation permanently deleted'];

        return back()->withNotify($notify);
    }


    public function printinvoice($id)
    {
      //  Gate::authorize('admin.quotation.printinvoice');

        $data['quotation'] = Quotation::find($id);
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($data['quotation']->customer_due);
        
        $data['quotation']->print = ($data['quotation']->print ?? 0) + 1;
        $data['quotation']->save();

        $pdf = PDF::loadView('admin.orders.quotations.invoice', $data);
        return $pdf->stream('invoice.pdf');

        // return view('admin.orders.orders.invoice',$data);
    }


    public function printchallan($id)
    {
      //  Gate::authorize('admin.quotation.printchallan');

        $data['quotation'] = Quotation::find($id);
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($data['quotation']->customer_due);

        $pdf = PDF::loadView('admin.orders.quotations.challan', $data);
        return $pdf->stream('challan.pdf');

        // return view('admin.orders.orders.invoice',$data);
    }

    public function productdemand(Request $request)
    {
        Gate::authorize('admin.quotation.productdemand');

        if ($request->date) {
            $data['date'] = $request->date;
            $data['format'] = $request->format;
            $data['orientation'] = $request->orientation;
            $data['type'] = $request->type;
            $data['searching'] = "Yes";

            $data['customers'] = User::select('users.id', 'users.name')
                ->join('quotations', 'users.id', '=', 'quotations.customer_id')
                ->whereDate('quotations.date', $request->date)
                ->where('type', 'customer')
                ->groupBy('users.id', 'users.name')
                ->get();

            $products = Product::select(
                'products.id',
                'products.name',
                'products.department_id',
                'departments.name as department_name',
                'quotation_details.qty',
                'quotations.customer_id'
            )
                ->join('departments', 'departments.id', '=', 'products.department_id')
                ->leftJoin('quotation_details', 'products.id', '=', 'quotation_details.product_id')
                ->leftJoin('quotations', 'quotations.id', '=', 'quotation_details.quotation_id')
                ->whereDate('quotations.date', $request->date)
                ->where('quotations.status','Active')
                ->groupBy('products.id', 'products.name', 'products.department_id', 'departments.name', 'quotations.customer_id')
                ->selectRaw('SUM(quotation_details.qty) as total_qty')
                ->orderBy('departments.name')
                ->orderBy('products.name')
                ->get();

            // Group by department_id and then product_id
            $data['products_by_department'] = $products->groupBy([
                'department_id',
                function ($item) {
                    return $item->id; // product_id
                }
            ]);
        } else {
            $data['searching'] = "No";
            $data['customers'] = [];
            $data['products_by_department'] = [];
        }

        if ($request->has('search')) {
            return view('admin.orders.quotations.productdemand', $data);
        } elseif ($request->has('pdf')) {
            ini_set('pcre.backtrack_limit', 10000000);
            $pdf = PDF::loadView('admin.orders.quotations.productdemand_pdf', $data, [], [
                'format' => $request->format,
                'orientation' => $request->orientation
            ]);
            return $pdf->stream('productdemand.pdf');
        } else {
            return view('admin.orders.quotations.productdemand', $data);
        }
    }
}
