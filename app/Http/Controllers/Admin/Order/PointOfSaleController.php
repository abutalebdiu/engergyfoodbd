<?php

namespace App\Http\Controllers\Admin\Order;

use App\Models\User;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Order\Quotation;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\HR\Employee;
use Illuminate\Support\Facades\Gate;
use App\Models\Distribution\Distribution;

class PointOfSaleController extends Controller
{
    public function create(Request $request)
    {
        Gate::authorize('admin.order.create') or Gate::authorize('admin.pointofsale.create');

        if ($request->quotation_id) {
            $data['quotation'] = Quotation::find($request->quotation_id);
        } else {
            $data['quotation'] = null;
        }
        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        $data['customers'] = User::where('type', 'customer')->orderBy('name', 'ASC')->get(['id', 'name', 'uid']);
        $data['employees'] = Employee::where('status', 'Active')->get();
        return view('admin.orders.orders.pos.point_of_sale', $data);
    }

    // public function store(Request $request)
    // {
    //     Gate::authorize('admin.order.store') or Gate::authorize('admin.pointofsale.store');

    //     // Validate the input
    //     $request->validate([
    //         'customer_id' => 'required|exists:users,id',
    //         'product_id' => 'required|array',
    //         'product_id.*' => 'exists:products,id',
    //         'product_qty' => 'required|array'
    //     ]);

    //     $input = $request->all();

    //     $filteredProductIds = [];
    //     $filteredProductQtys = [];
    //     $findcustomer = User::find($request->customer_id);
       
    //     $finddistribution = Distribution::find($findcustomer->distribution_id);

    //     if ($finddistribution) {
    //         $dis_pre_balance = $finddistribution->receivable($finddistribution->id);
    //     }
    //     else {
    //         $dis_pre_balance = 0;
    //     }

    //     foreach (bn2en($input['product_qty']) as $index => $qty) {
    //         if ($qty > 0) {
    //             $filteredProductIds[] = $input['product_id'][$index];
    //             $filteredProductQtys[] = $qty;
    //         }
    //     }

    //     $input['product_id'] = $filteredProductIds;
    //     $input['product_qty'] = $filteredProductQtys;

    //     if (count($filteredProductQtys) > 0) {
    //         DB::beginTransaction();
    //         try {

    //             $previousbalance = $findcustomer->receivable($findcustomer->id);

    //             $order = Order::create([
    //                 "date"              => $request->date ? $request->date : date('Y-m-d'),
    //                 "customer_id"       => $input['customer_id'],
    //                 "quotation_id"      => $request->quotation_id,
    //                 "salesman_id"       => $request->salesman_id,
    //                 "driver_id"         => $request->driver_id,
    //                 "sub_total"         => 0,
    //                 "return_amount"     => 0,
    //                 "net_amount"        => 0,
    //                 "commission"        => 0,
    //                 "paid_amount"       => 0,
    //                 "payment_status"    => "Unpaid",
    //                 "commission_status" => "Unpaid",
    //                 "status"            => "Active",
    //                 "entry_id"          => auth('admin')->user()->id
    //             ]);

    //             $order->oid = "OID000" . $order->id;
    //             $order->save();
                
                
    //             if($request->quotation_id)
    //             {
    //                 $quotation = Quotation::find($request->quotation_id);
    //                 $quotation->order_id = $order->id;
    //                 $quotation->save();
    //             }
        

    //             $ref_commission = 0;


    //             if ($input['product_id'] && $input['product_qty']) {
    //                 foreach ($input['product_id'] as $key => $value) {
    //                     $product = Product::find($value);
    //                     $product_commission = 0;

    //                     for ($i = 0; $i < $input['product_qty'][$key]; $i++) {
    //                         if ($input['customer_id']) {
    //                             $product_commission += calculateCommission($value, $input['customer_id']);
    //                         }
    //                     }
    //                     $getproductprice =  calculateProductPrice($product->id, $input['customer_id']);
    //                     $ref_commission += $product_commission;
    //                     $order->orderdetail()->create([
    //                         "product_id"            => $product->id,
    //                         "qty"                   => $input['product_qty'][$key],
    //                         "price"                 => $getproductprice,
    //                         "amount"                => floor($getproductprice * $input['product_qty'][$key]),
    //                         "product_commission"    => $product_commission
    //                     ]);

    //                     $product->qty = $product->getstock($value);
    //                     $product->save();
    //                 }
    //             }

    //             $order->sub_total               = $order->orderdetail->sum('amount');
    //             $order->return_amount           = 0;
    //             $order->net_amount              = $order->orderdetail->sum('amount');
    //             $order->commission              = ($order->orderdetail->sum('product_commission'));
    //             // Monthly

    //             $newdue = 0;

    //             if ($findcustomer->commission_type == "Monthly") {
    //                 $order->grand_total             = $order->orderdetail->sum('amount');
    //                 $newdue                         = $order->orderdetail->sum('amount');
    //             } else {
    //                 $order->commission_status       = "Paid";
    //                 $order->grand_total             = $order->orderdetail->sum('amount') - ($order->orderdetail->sum('product_commission'));
    //                 $newdue                         = $order->orderdetail->sum('amount') - ($order->orderdetail->sum('product_commission'));
    //             }

    //             $order->previous_due            = $previousbalance;
    //             $order->order_due               = $newdue;
    //             $order->customer_due            = $previousbalance + $newdue;
    //             $order->save();

    //             // Check merketer Commission
    //             if ($findcustomer->reference_id) {
    //                 $order->marketer_id          = $findcustomer->reference_id;
    //                 $order->marketer_commission = round((optional($findcustomer->reference)->amount * $order->net_amount) / 100, 2);
    //                 $order->save();
    //             }
                
                
    //              // store distribution commission
    //             if ($finddistribution) {
    //                 $order->distribution_id     = $finddistribution->id;
    //                 $order->dis_pre_due_total   = $dis_pre_balance;
    //                 $order->dis_per_due_total   = $dis_pre_balance + $order->grand_total;
    //                 $order->save();
    //             }

    //             DB::commit();

    //             if($request->ajax()){
    //                 return response()->json([
    //                     "status" => true,
    //                     "data" => [],
    //                     "redirect"=> route('admin.order.show', $order->id),
    //                     "message" => "Order created successfully!"
    //                 ], 201);
    //             }

    //             $notify[] = ['success', "Order created successfully"];
    //             return to_route('admin.order.show', $order->id)->withNotify($notify)->with('message', 'success');

    //         } catch (\Exception $e) {
    //             DB::rollBack();

    //             if($request->ajax()){
    //                 return response()->json([
    //                     "status" => true,
    //                     "data" => [],
    //                     "redirect"=> '',
    //                     "message" => "An error occurred while processing your request."
    //                 ], 500);
    //             }


    //             $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
    //             return back()->withNotify($notify);
    //         }
    //     } else {
    //         $notify[] = ['error', "Please select products"];

    //         if($request->ajax()){
    //             return response()->json([
    //                 "status" => true,
    //                 "data" => [],
    //                 "redirect"=> '',
    //                 "message" => "Please select products"
    //             ], 404);
    //         }

    //         return back()->withNotify($notify);
    //     }
    // }
    
    public function store(Request $request)
    {
     
        $request->validate([
            'customer_id'   => 'required|exists:users,id',
            'product_id'    => 'required|array',
            'product_id.*'  => 'exists:products,id',
            'product_qty'   => 'required|array'
        ]);
    
        $customer = User::findOrFail($request->customer_id);
        $distribution = Distribution::find($customer->distribution_id);
    
        $previousDue = $customer->receivable($customer->id);
        $disPreDue   = $distribution ? $distribution->receivable($distribution->id) : 0;
    
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
                ], 422)
                : back()->withNotify([['error', 'Please select products']]);
        }
    
        DB::beginTransaction();
        try {
    
            $order = Order::create([
                "date"              => $request->date ?? now()->format('Y-m-d'),
                "customer_id"       => $customer->id,
                "quotation_id"      => $request->quotation_id,
                "salesman_id"       => $request->salesman_id,
                "driver_id"         => $request->driver_id,
                "sub_total"         => 0,
                "return_amount"     => 0,
                "net_amount"        => 0,
                "commission"        => 0,
                "paid_amount"       => 0,
                "payment_status"    => "Unpaid",
                "commission_status" => "Unpaid",
                "status"            => "Active",
                "entry_id"          => auth('admin')->id()
            ]);
    
            $order->update([
                'oid' => "OID000" . $order->id
            ]);
    
            // attach quotation
            if ($request->quotation_id) {
                Quotation::where('id', $request->quotation_id)
                    ->update(['order_id' => $order->id]);
            }
    
            $subTotal        = 0;
            $totalCommission = 0;
    
            foreach ($products as $item) {
    
                $product = Product::findOrFail($item['product_id']);
    
                $price = calculateProductPrice($product->id, $customer->id);
    
                $commissionPerUnit = calculateCommission($product->id, $customer->id);
                $productCommission = $commissionPerUnit * $item['qty'];
    
                $amount = floor($price * $item['qty']);
    
                $order->orderdetail()->create([
                    "product_id"         => $product->id,
                    "qty"                => $item['qty'],
                    "price"              => $price,
                    "amount"             => $amount,
                    "product_commission" => $productCommission
                ]);
    
                $subTotal        += $amount;
                $totalCommission += $productCommission;
    
                // stock update
                $product->update([
                    'qty' => $product->getstock($product->id)
                ]);
            }
    
            // commission logic
            if ($customer->commission_type === "Monthly") {
                $grandTotal = $subTotal;
                $orderDue   = $subTotal;
            } else {
                $order->commission_status = "Paid";
                $grandTotal = $subTotal - $totalCommission;
                $orderDue   = $grandTotal;
            }
    
            $order->update([
                'sub_total'    => $subTotal,
                'return_amount'=> 0,
                'net_amount'   => $subTotal,
                'commission'   => $totalCommission,
                'grand_total'  => $grandTotal,
                'previous_due' => $previousDue,
                'order_due'    => $orderDue,
                'customer_due' => $previousDue + $orderDue,
            ]);
    
            // marketer commission
            if ($customer->reference_id) {
                $order->update([
                    'marketer_id'         => $customer->reference_id,
                    'marketer_commission'=> round(
                        (optional($customer->reference)->amount * $order->net_amount) / 100,
                        2
                    )
                ]);
            }
    
            // distribution due
            if ($distribution) {
                $order->update([
                    'distribution_id'   => $distribution->id,
                    'dis_pre_due_total' => $disPreDue,
                    'dis_per_due_total' => $disPreDue + $grandTotal,
                ]);
            }
    
            DB::commit();
    
            return $request->ajax()
                ? response()->json([
                    "status" => true,
                    "redirect" => route('admin.order.show', $order->id),
                    "message" => "Order created successfully!"
                ], 201)
                : to_route('admin.order.show', $order->id)
                    ->withNotify([['success', 'Order created successfully']]);
    
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


    public function edit($id)
    {
        Gate::authorize('admin.order.edit') or Gate::authorize('admin.pointofsale.edit');
        $data['order'] = Order::find($id);
        $data['products'] = Product::where('status', 'Active')->get();
        return view('admin.orders.orders.pos.edit_point_of_sale', $data);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin.order.update') or Gate::authorize('admin.pointofsale.update');
        $request->validate([
            'customer_id'       => 'required|exists:users,id',
            'product_id'        => 'required|array',
            'product_id.*'      => 'exists:products,id',
            'product_qty'       => 'required|array',
            'product_qty.*'     => 'min:0'
        ]);

        $input = $request->all();
        $findcustomer = User::find($request->customer_id);
        $filteredProductIds     = [];
        $filteredProductQtys    = [];

        foreach (bn2en($input['product_qty']) as $index => $qty) {
            if ($qty > 0) {
                $filteredProductIds[] = $input['product_id'][$index];
                $filteredProductQtys[] = $qty;
            }
        }

        $input['product_id']    = $filteredProductIds;
        $input['product_qty']   = $filteredProductQtys;

        // Find the order by ID
        $order = Order::findOrFail($id);

        DB::beginTransaction();
        try {

            // Remove existing order details
            $order->orderdetail()->delete();

            $ref_commission = 0;


            if ($input['product_id'] && $input['product_qty']) {
                foreach ($input['product_id'] as $key => $value) {
                    $product = Product::find($value);
                    $product_commission = 0;

                    for ($i = 0; $i < $input['product_qty'][$key]; $i++) {
                        if ($input['customer_id']) {
                            $product_commission += calculateCommission($value, $input['customer_id']);
                        }
                    }
                    $getproductprice =  calculateProductPrice($value, $input['customer_id']);
                    $ref_commission += $product_commission;
                    $order->orderdetail()->create([
                        "product_id"        => $product->id,
                        "qty"               => $input['product_qty'][$key],
                        "price"             => $getproductprice,
                        "amount"            => floor($getproductprice * $input['product_qty'][$key]),
                        "product_commission" => $product_commission
                    ]);
                }
            }

            $order->sub_total         = $order->orderdetail->sum('amount');
            $order->totalamount       = $order->orderdetail->sum('amount');
            $order->net_amount        = $order->orderdetail->sum('amount');
            $order->save();

            $order->commission_amount =  $order->orderdetail->sum('product_commission');
            $order->without_commission_net_amount = $order->totalamount - $order->orderdetail->sum('product_commission');
            $order->save();

            if ($findcustomer->commission_type == "Monthly") {
                $order->customer_previous_total_due =  $order->customer->receivable($order->customer_id);
                $order->due_amount                  =  $order->customer->receivable($order->customer_id);
                $order->customer_total_due          =  $order->customer->receivable($order->customer_id);
                $order->due_balance          =  $order->customer->receivable($order->customer_id);
                $order->save();
            } else {
                $order->customer_previous_total_due = $order->customer->receivable($order->customer_id) - $order->net_amount;
                $order->due_amount                  = $order->customer->receivable($order->customer_id) - $order->without_commission_net_amount - $order->orderdetail->sum('product_commission');
                $order->customer_total_due          = $order->customer->receivable($order->customer_id) - $order->orderdetail->sum('product_commission');
                $order->due_balance          = $order->customer->receivable($order->customer_id) - $order->orderdetail->sum('product_commission');
                $order->commission_status           = "Paid";
                $order->save();
            }

            if ($input['customer_id']) {
                storeCommission($order, $ref_commission, 'commission_amount');
            }


            DB::commit();

            $notify[] = ['success', 'Order updated successfully'];
            return back()->withNotify($notify)->with('message', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', 'An error occurred while processing your request. ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
