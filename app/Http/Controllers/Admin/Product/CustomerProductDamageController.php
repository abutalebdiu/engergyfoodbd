<?php

namespace App\Http\Controllers\Admin\Product;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Product\CustomerProductDamage;
use App\Models\Product\CustomerProductDamageDetail;
use PDF;

class CustomerProductDamageController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.customerproductdamage.list');
        $data['customers'] = User::where('type', 'customer')->orderBy('name', 'ASC')->get(['id', 'name', 'uid']);
        $data['damages'] = CustomerProductDamage::latest()->get();

        $query = CustomerProductDamage::query();

        if ($request->product_id) {
            $data['product_id']        = $request->product_id;
            $query->where('product_id', $request->product_id);
        }

        if ($request->customer_id) {
            $data['customer_id']        = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ]);
        }

        $data['damages'] = $query->latest()->paginate(100);


        if ($request->has('search')) {
            return view('admin.orders.customerproductdamages.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.customerproductdamages.product_damage_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {

        } else {
            return view('admin.orders.customerproductdamages.view', $data);
        }


    }

    public function create()
    {
        Gate::authorize('admin.customerproductdamage.create');
        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        $data['customers'] = User::where('type', 'customer')->orderBy('name', 'ASC')->get(['id', 'name', 'uid']);
        return view('admin.orders.customerproductdamages.create', $data);
    }

    public function store(Request $request)
    {

        Gate::authorize('admin.customerproductdamage.create');
        $input = $request->all();

        // Validate the input
        $request->validate([
            'date'              => 'required',
            'customer_id'       => 'required',
            'product_id'        => 'required|array',
            'product_id.*'      => 'exists:products,id',
            'product_qty'       => 'required|array',
            'product_qty.*'     => 'min:0'
        ]);

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

        if (count($filteredProductQtys) > 0) {
            DB::beginTransaction();
            try {

                $customerproductdamage = new CustomerProductDamage();
                $customerproductdamage->date            = $request->date;
                $customerproductdamage->customer_id     = $request->customer_id;
                $customerproductdamage->qty             = 0;
                $customerproductdamage->total_amount    = 0;
                $customerproductdamage->note            = $request->note;
                $customerproductdamage->entry_id        = auth('admin')->user()->id;
                $customerproductdamage->save();

                if ($input['product_id'] && $input['product_qty']) {
                    foreach ($input['product_id'] as $key => $value) {

                        $getproductprice =  calculateProductPrice($value, $input['customer_id']);

                        $customerproductdamagedetail                = new CustomerProductDamageDetail();
                        $customerproductdamagedetail->customer_product_damage_id    = $customerproductdamage->id;
                        $customerproductdamagedetail->product_id    = $value;
                        $customerproductdamagedetail->qty           = $input['product_qty'][$key];
                        $customerproductdamagedetail->price         = $getproductprice;
                        $customerproductdamagedetail->amount        = $getproductprice * $input['product_qty'][$key];
                        $customerproductdamagedetail->entry_id      = auth('admin')->user()->id;
                        $customerproductdamagedetail->save();

                        $product = Product::find($value);
                        $product->qty = $product->getstock($value);
                        $product->save();
                    }
                }

                $customerproductdamage->qty             = $customerproductdamage->customerproductdamagedetail->sum('qty');
                $customerproductdamage->total_amount    = $customerproductdamage->customerproductdamagedetail->sum('amount');
                $customerproductdamage->save();


                DB::commit();

                $notify[] = ['success', 'successfully Added'];
                return to_route('admin.customerproductdamage.index')->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select products"];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'successfully Added'];
        return to_route('admin.customerproductdamage.index')->withNotify($notify);
    }

    public function show(CustomerProductDamage $customerproductdamage)
    {
        Gate::authorize('admin.customerproductdamage.show');
        return view('admin.orders.customerproductdamages.show', compact('customerproductdamage'));
    }

    public function edit(CustomerProductDamage $customerproductdamage)
    {
        Gate::authorize('admin.customerproductdamage.edit');
        $data['productswithgroupes']    = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        $data['customers']              = User::where('type', 'customer')->orderBy('name', 'ASC')->get(['id', 'name', 'uid']);
        return view('admin.orders.customerproductdamages.edit', compact('customerproductdamage'), $data);
    }

    public function update(Request $request, CustomerProductDamage $customerproductdamage)
    {
        Gate::authorize('admin.customerproductdamage.update');
        CustomerProductDamageDetail::where('customer_product_damage_id', $customerproductdamage->id)->delete();

        $input = $request->all();

        // Validate the input
        $request->validate([
            'date'              => 'required',
            'customer_id'       => 'required',
            'product_id'        => 'required|array',
            'product_id.*'      => 'exists:products,id',
            'product_qty'       => 'required|array',
            'product_qty.*'     => 'min:0'
        ]);

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

        if (count($filteredProductQtys) > 0) {
            DB::beginTransaction();
            try {

                $customerproductdamage->date        = $request->date;
                $customerproductdamage->customer_id = $request->customer_id;
                $customerproductdamage->note        = $request->note;
                $customerproductdamage->qty         = 0;
                $customerproductdamage->total_amount = 0;
                $customerproductdamage->entry_id    = auth('admin')->user()->id;
                $customerproductdamage->save();

                if ($input['product_id'] && $input['product_qty']) {
                    foreach ($input['product_id'] as $key => $value) {

                        $getproductprice =  calculateProductPrice($value, $input['customer_id']);

                        $customerproductdamagedetail                = new CustomerProductDamageDetail();
                        $customerproductdamagedetail->customer_product_damage_id    = $customerproductdamage->id;
                        $customerproductdamagedetail->product_id    = $value;
                        $customerproductdamagedetail->qty           = $input['product_qty'][$key];
                        $customerproductdamagedetail->price         = $getproductprice;
                        $customerproductdamagedetail->amount        = $getproductprice * $input['product_qty'][$key];
                        $customerproductdamagedetail->entry_id      = auth('admin')->user()->id;
                        $customerproductdamagedetail->save();

                        $product = Product::find($value);
                        $product->qty = $product->getstock($value);
                        $product->save();
                    }
                }

                $customerproductdamage->qty             = $customerproductdamage->customerproductdamagedetail->sum('qty');
                $customerproductdamage->total_amount    = $customerproductdamage->customerproductdamagedetail->sum('amount');
                $customerproductdamage->save();


                DB::commit();

                $notify[] = ['success', 'Successfully Updated'];
                return to_route('admin.customerproductdamage.index')->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select products"];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'successfully Updated'];
        return to_route('admin.customerproductdamage.index')->withNotify($notify);
    }

    public function destroy(CustomerProductDamage $customerproductdamage)
    {
        Gate::authorize('admin.customerproductdamage.destroy');
        CustomerProductDamageDetail::where('customer_product_damage_id', $customerproductdamage->id)->delete();
        $customerproductdamage->delete();
        $notify[] = ['success', "Deleted successfully"];
        return back()->withNotify($notify);
    }
}
