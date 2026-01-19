<?php

namespace App\Http\Controllers\Admin\Order;

use Carbon\Carbon;
use PDF;
use App\Models\User;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Order\OrderReturn;
use App\Exports\OrderReturnExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order\OrderReturnDetail;
use App\Models\Account\OrderReturnPayment;
use Rakibhstu\Banglanumber\NumberToBangla;

class OrderReturnController extends Controller
{

    public function index(Request $request)
    {
        // Gate::authorize('app.orderreturn.list');

        $data['customers'] = User::where('type', 'customer')->get(['id', 'name']);
        $query = OrderReturn::query();
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


        $data['orderreturns'] = $query->latest()->get();
        if ($request->has('search')) {
            return view('admin.orders.orderreturns.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.orderreturns.orderreturn_pdf', $data);
            return $pdf->stream('orderreturn_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new OrderReturnExport($data), 'Orderreturn_list.xlsx');
        } else {
            return view('admin.orders.orderreturns.view', $data);
        }
    }

    public function create(Request $request)
    {
        //  Gate::authorize('app.orderreturn.create');

        if ($request->order_id) {
            $data['order'] = Order::find($request->order_id);
        } else {
            $data['order'] = null;
        }
        $data['customers'] = User::where('type', 'customer')->orderby('name', 'asc')->where('status', 1)->get();
        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        return view('admin.orders.orderreturns.create', $data);
    }

    public function store(Request $request)
    {
        // Gate::authorize('app.orderreturn.store');

        // Validate the input
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'product_qty' => 'required|array'
        ]);

        $input = $request->all();

        $filteredProductIds = [];
        $filteredProductQtys = [];

        $findcustomer = User::find($request->customer_id);

        foreach (bn2en($input['product_qty']) as $index => $qty) {
            if ($qty > 0) {
                $filteredProductIds[] = $input['product_id'][$index];
                $filteredProductQtys[] = $qty;
            }
        }

        if (count($filteredProductQtys) > 0) {
        DB::beginTransaction();
        try {
            $orderreturn = new OrderReturn();
            $orderreturn->date      = $request->date ? $request->date : Date('Y-m-d');
            $orderreturn->order_id      =  $request->order_id ? $request->order_id : null;
            $orderreturn->customer_id   = $request->customer_id;
            $orderreturn->payment_status = $request->order_id ? "Paid" : "Unpaid";
            $orderreturn->entry_id      = auth('admin')->user()->id;
            $orderreturn->save();

            $input = $request->all();
            $ref_commission = 0;

            // return $filteredProductIds;

            if ($filteredProductIds && $filteredProductQtys) {

                foreach ($filteredProductIds as $key => $value) {
                    $product = Product::find($value);
                    $product_commission = 0;
                    for ($i = 0; $i < $filteredProductQtys[$key]; $i++) {


                        if ($input['customer_id']) {
                            $product_commission += calculateCommission($value, $input['customer_id']);
                        }
                    }

                    $getproductprice =  calculateProductPrice($value, $input['customer_id']);

                    $ref_commission += $product_commission;

                    $orderreturn->orderreturndetail()->create([
                        'order_return_id'   => $orderreturn->id,
                        "product_id"        => $product->id,
                        "qty"               => $filteredProductQtys[$key],
                        "price"             => $getproductprice,
                        "amount"            => floor($getproductprice * $filteredProductQtys[$key]),
                        "product_commission"=> $product_commission
                    ]);
                }
            }

            $orderreturn->commission                        = $orderreturn->orderreturndetail->sum('product_commission');
            $orderreturn->sub_total                         = $orderreturn->orderreturndetail->sum('amount');
            $orderreturn->totalamount                       = $orderreturn->orderreturndetail->sum('amount');
            $orderreturn->net_amount                        = $orderreturn->orderreturndetail->sum('amount') - $orderreturn->orderreturndetail->sum('product_commission');
            $orderreturn->save();

            if ($request->order_id){
                $order = Order::find($request->order_id);
                $totalnetamount                  = $order->net_amount - $orderreturn->orderreturndetail->sum('amount');
                $order->return_amount            = $orderreturn->orderreturndetail->sum('amount');
                $order->net_amount               = $totalnetamount;
                
                if ($findcustomer->commission_type == "Monthly") {
                    $order->grand_total          = $totalnetamount + $orderreturn->orderreturndetail->sum('product_commission');
                    $order->order_due            = $totalnetamount + $orderreturn->orderreturndetail->sum('product_commission');
                } else {
                    $order->grand_total          = $totalnetamount  - $order->commission + $orderreturn->orderreturndetail->sum('product_commission');
                    $order->order_due            = $totalnetamount  - $order->commission + $orderreturn->orderreturndetail->sum('product_commission');
                }
                $order->save();

                $order->customer_due            =  $findcustomer->receivable($findcustomer->id);
                $order->save();
            }

            // Check merketer Commission
            if ($findcustomer->reference_id) {
                $order->marketer_id          = $findcustomer->reference_id;
                $order->marketer_commission = round((optional($findcustomer->reference)->amount * $order->net_amount) / 100, 2);
                $order->save();
            }


            DB::commit();

            $notify[] = ['success', "Order Return Success"];

            if ($request->order_id) {
                return to_route('admin.order.show', $request->order_id)->withNotify($notify)->with('message', 'success');
            }
            
            return to_route('admin.orderreturn.index')->withNotify($notify)->with('message', 'success');
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select products"];
            return back()->withNotify($notify);
        }
    }

    public function show(OrderReturn $orderreturn)
    {
        Gate::authorize('admin.orderreturn.show');

        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($orderreturn->net_amount);

        return view('admin.orders.orderreturns.show', compact('orderreturn'), $data);
    }

    public function edit(OrderReturn $orderreturn)
    {
        Gate::authorize('admin.orderreturn.edit');
        return view('admin.orders.orderreturns.edit', compact('orderreturn'));
    }

    public function update(Request $request, OrderReturn $orderreturn)
    {
        $request->validate([
            'date' => 'required',
        ]);

        $orderreturn->update(['date' => $request->date]);

        $notify[] = ['success', 'Order Return successfully Updated'];
        return to_route('admin.orderreturn.index')->withNotify($notify);
    }

    public function destroy(OrderReturn $orderreturn)
    {
        Gate::authorize('admin.orderreturn.delete');

        OrderReturnDetail::where('order_return_id', $orderreturn->id)->delete();
        OrderReturnPayment::where('order_return_id', $orderreturn->id)->delete();
        $orderreturn->delete();
        $notify[] = ['success', "Order Return deleted successfully"];
        return back()->withNotify($notify);
    }
}
