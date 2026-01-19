<?php

namespace App\Http\Controllers\Admin\Order\Quotation;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order\Quotation;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Order\QuotationDetail;
use Rakibhstu\Banglanumber\NumberToBangla;

class QuotationController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.quotation.list');

        $data['customers'] = User::where('type', 'customer')->orderBy('name', 'ASC')->get(['id', 'name', 'uid']);

        $query = Quotation::query();

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

        $data['orders'] = $query->latest()->get();

        if ($request->has('search')) {
            return view('admin.orders.quotations.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.quotations.quotation_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } else {

            return view('admin.orders.quotations.view', $data);
        }
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
       // Gate::authorize('admin.quotation.create');
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

        $input['product_id'] = $filteredProductIds;
        $input['product_qty'] = $filteredProductQtys;

        if (count($filteredProductQtys) > 0) {
            DB::beginTransaction();
            try {

                $previousbalance = $findcustomer->receivable($findcustomer->id);

                $order = Quotation::create([
                    "date"              => $request->date ? $request->date : date('Y-m-d'),
                    "customer_id"       => $input['customer_id'],
                    "sub_total"         => 0,
                    "net_amount"        => 0,
                    "commission"        => 0,
                    "commission_status" => "Unpaid",
                    "status"            => "Active",
                    "entry_id"          => auth('admin')->user()->id
                ]);

                $order->qid = "QID000" . $order->id;
                $order->save();

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
                        $getproductprice =  calculateProductPrice($product->id, $input['customer_id']);
                        $ref_commission += $product_commission;
                        $order->quotationdetail()->create([
                            "product_id"        => $product->id,
                            "qty"               => $input['product_qty'][$key],
                            "price"             => $getproductprice,
                            "amount"            => floor($getproductprice * $input['product_qty'][$key]),
                            "product_commission" => $product_commission,
                            "entry_id"          => auth('admin')->user()->id
                        ]);

                        $product->qty = $product->getstock($value);
                        $product->save();
                    }
                }

                $order->sub_total               = $order->quotationdetail->sum('amount');
                $order->net_amount              = $order->quotationdetail->sum('amount');
                $order->commission              = $order->quotationdetail->sum('product_commission');
                // Monthly

                $newdue = 0;

                if ($findcustomer->commission_type == "Monthly") {
                    $order->grand_total             = $order->quotationdetail->sum('amount');
                    $newdue                         = $order->quotationdetail->sum('amount');
                } else {
                    $order->commission_status       = "Paid";
                    $order->grand_total             = $order->quotationdetail->sum('amount') - $order->quotationdetail->sum('product_commission');
                    $newdue                         = $order->quotationdetail->sum('amount') - $order->quotationdetail->sum('product_commission');
                }

                $order->previous_due            = $previousbalance;
                $order->order_due               = $newdue;
                $order->customer_due            = $previousbalance + $newdue;
                $order->save();

                DB::commit();

                $notify[] = ['success', "Quotation created successfully"];
                return to_route('admin.quotation.show', $order->id)->withNotify($notify)->with('message', 'success');
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

    public function show(Quotation $quotation)
    {
      //  Gate::authorize('admin.quotation.show');
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($quotation->customer_due);
        return view('admin.orders.quotations.show', compact('quotation'), $data);
    }

    public function edit($id)
    {
      //  Gate::authorize('admin.quotation.edit');
        $data['quotation'] = Quotation::find($id);
        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        return view('admin.orders.quotations.edit', $data);
    }

    public function update(Request $request, Quotation $quotation)
    {
        // Gate::authorize('admin.quotation.update');
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

        $input['product_id'] = $filteredProductIds;
        $input['product_qty'] = $filteredProductQtys;

        if (count($filteredProductQtys) > 0) {
            DB::beginTransaction();
            try {

                $previousbalance = $findcustomer->receivable($findcustomer->id);

                $quotation->quotationdetail()->delete();
                $order = Quotation::find($quotation->id);

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
                        $getproductprice =  calculateProductPrice($product->id, $input['customer_id']);
                        $ref_commission += $product_commission;
                        $order->quotationdetail()->create([
                            "product_id"        => $product->id,
                            "qty"               => $input['product_qty'][$key],
                            "price"             => $getproductprice,
                            "amount"            => floor($getproductprice * $input['product_qty'][$key]),
                            "product_commission" => $product_commission,
                            "entry_id"          => auth('admin')->user()->id
                        ]);

                        $product->qty = $product->getstock($value);
                        $product->save();
                    }
                }

                $order->sub_total               = $order->quotationdetail->sum('amount');
                $order->net_amount              = $order->quotationdetail->sum('amount');
                $order->commission              = $order->quotationdetail->sum('product_commission');
                // Monthly

                $newdue = 0;

                if ($findcustomer->commission_type == "Monthly") {
                    $order->grand_total             = $order->quotationdetail->sum('amount');
                    $newdue                         = $order->quotationdetail->sum('amount');
                } else {
                    $order->commission_status       = "Paid";
                    $order->grand_total             = $order->quotationdetail->sum('amount') - $order->quotationdetail->sum('product_commission');
                    $newdue                         = $order->quotationdetail->sum('amount') - $order->quotationdetail->sum('product_commission');
                }

                $order->previous_due            = $previousbalance;
                $order->order_due               = $newdue;
                $order->customer_due            = $previousbalance + $newdue;
                $order->save();

                DB::commit();

                $notify[] = ['success', "Quotation created successfully"];
                return to_route('admin.quotation.show', $order->id)->withNotify($notify)->with('message', 'success');
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


    public function destroy(Quotation $quotation)
    {
        Gate::authorize('admin.quotation.destroy');
        QuotationDetail::where('quotation_id', $quotation->id)->delete();
        $quotation->delete();
        $notify[] = ['success', "Order deleted successfully"];
        return back()->withNotify($notify);
    }


    public function printinvoice($id)
    {
      //  Gate::authorize('admin.quotation.printinvoice');

        $data['quotation'] = Quotation::find($id);
        $numto = new NumberToBangla();
        $data['banglanumber'] = $numto->bnWord($data['quotation']->customer_due);

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

        // Check if date is provided
        if ($request->date) {
            $data['date'] = $request->date;
            $data['format']         = $request->format;
            $data['orientation']    = $request->orientation;
            $data['type']           = $request->type;
            $data['searching'] = "Yes";

            // Get customers and their ordered products
            $data['customers'] = User::select('users.id', 'users.name')
                ->join('quotations', 'users.id', '=', 'quotations.customer_id')
                ->whereDate('quotations.date', $request->date)
                ->groupBy('users.id', 'users.name')
                ->where('type', 'customer')
                ->get();

            // Get products and customer-wise ordered quantities
            $data['products'] = Product::select('products.id', 'products.name')
                ->leftJoin('quotation_details', 'products.id', '=', 'quotation_details.product_id')
                ->leftJoin('quotations', 'quotations.id', '=', 'quotation_details.quotation_id')
                ->selectRaw('SUM(quotation_details.qty) as total_qty, products.id as product_id, quotations.customer_id')
                ->whereDate('quotations.date', $request->date)
                ->groupBy('products.id', 'products.name', 'quotations.customer_id')
                ->orderBy('products.id', 'asc')
                ->get()
                ->groupBy('product_id');
        } else {
            $data['searching'] = "No";
            $data['customers'] = [];
            $data['products'] = [];
        }

        if ($request->has('search')) {
            return view('admin.orders.quotations.productdemand', $data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.orders.quotations.productdemand_pdf', $data, [], [
                'format' => $request->format, // or 'A3', 'Letter', etc.
                'orientation' => $request->orientation // 'P' for Portrait, 'L' for Landscape
            ]);
            return $pdf->stream('productdemand.pdf');
        } else {
            return view('admin.orders.quotations.productdemand', $data);
        }
    }
}
