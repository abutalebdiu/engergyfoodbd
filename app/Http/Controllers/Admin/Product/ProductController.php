<?php

namespace App\Http\Controllers\Admin\Product;

use PDF;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Exports\ProductExport;
use App\Models\DailyProduction;
use App\Models\Product\Product;
use App\Models\Order\OrderDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order\QuotationDetail;
use App\Models\Product\ProductRecipe;
use App\Models\Commission\ReferenceCommision;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.product.list');

        $data['departments'] = Department::orderBy('position', 'ASC')
            ->where('is_p', 'Yes')
            ->get();

        $query = Product::with('department');

        if ($request->code) {
            $data['code'] = $request->code;
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->name) {
            $data['name'] = $request->name;
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->department_id) {
            $data['department_id'] = $request->department_id;
            $query->where('department_id', $request->department_id);
        }
        


        // Handle AJAX request
        if ($request->ajax()) {
            $productList = $query->latest()->paginate(10)->withQueryString();

            $data['productss'] = $productList;
            $data['productswithgroupes'] = $productList->getCollection()->groupBy('department_id');

            return response()->json([
                "status" => true,
                "message" => "Data view",
                "html"   => view('admin.products.products.inc.product_table', $data)->render(),
            ]);
        }


        $data['productswithgroupes'] = $query->get()->groupBy('department_id');

        $productList = $query->latest()->get();
        $data['productss'] = $productList;

        // Export conditions
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.products.products.product_pdf', $data, [], [
                'format'      => 'legal',
                'orientation' => 'Portrait'
            ]);
            return $pdf->stream('product_list.pdf');
        }
        elseif ($request->has('pdf2')) {
            $pdf = PDF::loadView('admin.products.products.product_list_pdf', $data, [], [
                'format' => 'legal'
            ]);
            return $pdf->stream('product_list.pdf');
        }
        elseif ($request->has('pdf3')) {
            $pdf = PDF::loadView('admin.products.products.product_stock_pdf', $data, [], [
                'format' => 'A4'
            ]);
            return $pdf->stream('product_stock.pdf');
        }
        elseif ($request->has('recipe')) {
            $pdf = PDF::loadView('admin.products.products.product_recipe_list_pdf', $data);
            return $pdf->stream('product_recipe_list.pdf');
        }
        elseif ($request->has('excel')) {
            return Excel::download(new ProductExport($query->get()), 'product_list.xlsx');
        }

        // Default view
        return view('admin.products.products.view', $data);

    }

    public function create()
    {
        Gate::authorize('admin.product.create');
        $data['departments']    = Department::orderBy('position', 'ASC')->get();
        $data['ppitems']        = Item::whereIn('item_category_id',[2,6])->get(['id','name']);
        $data['strickeritems']  = Item::whereIn('item_category_id',[7])->get(['id','name']);
        $data['boxitems']       = Item::whereIn('item_category_id',[4])->get(['id','name']);
        return view('admin.products.products.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.product.store');
        $request->validate([
            'name' => 'required',
        ]);

        $product = Product::create(array_merge($request->except('_token', 'image'), [
            'sale_price'    => bn2en($request->sale_price),
            'shop_price'    => bn2en($request->shop_price),
            'retail_price'  => bn2en($request->retail_price),
            'pp_weight'     => bn2en($request->pp_weight),
            'yeast'         => bn2en($request->yeast),
            'entry_id'      => auth('admin')->user()->id,
            'status'        => 'Active'
        ]));


        $customers =  User::where('type', 'customer')->get(['id', 'commission']);

        foreach ($customers as $customer) {
            $refercommission = new ReferenceCommision();
            $refercommission->user_id    = $customer->id;
            $refercommission->product_id = $product->id;
            $refercommission->price      = bn2en($product->sale_price);
            $refercommission->amount     = bn2en($customer->commission ? $customer->commission : 0);
            $refercommission->type       = 'Percentage';
            $refercommission->entry_id   = auth('admin')->user()->id;
            $refercommission->save();
        }

        $notify[] = ['success', 'Product successfully Added'];
        return to_route('admin.product.index')->withNotify($notify);
    }

    public function show(Product $product)
    {
        Gate::authorize('admin.product.show');
        $data['items'] = Item::orderby('name', 'ASC')->get();
        $data['units'] = Unit::get();
        return view('admin.products.products.show', compact('product'), $data);
    }

    public function edit(Product $product)
    {
        Gate::authorize('admin.product.edit');
        $data['departments'] = Department::orderBy('position', 'ASC')->get();
        $data['ppitems']        = Item::whereIn('item_category_id',[2,6])->get(['id','name']);
        $data['strickeritems']  = Item::whereIn('item_category_id',[7])->get(['id','name']);
        $data['boxitems']       = Item::whereIn('item_category_id',[4])->get(['id','name']);
        return view('admin.products.products.edit', compact('product'), $data);
    }

    public function update(Request $request, Product $product)
    {
        Gate::authorize('admin.product.update');
        $request->validate([
            'name' => 'required',
        ]);

        $product->update(array_merge($request->except('_token', 'image', 'type','commission'), [
            'sale_price'    => bn2en($request->sale_price),
            'shop_price'    => bn2en($request->shop_price),
            'retail_price'  => bn2en($request->retail_price),
            'pp_weight'     => bn2en($request->pp_weight),
            'yeast'         => bn2en($request->yeast),
            'edit_id'       => auth('admin')->user()->id,
            'edit_at'       => now(),
        ]));

        if ($request->type == 2) {
            ReferenceCommision::where('product_id',$product->id)->update(['price' => $request->sale_price]);
        }
        if ($request->type == 3) {
            ReferenceCommision::where('product_id',$product->id)->update(['amount' => $request->commission]);
        }

        // foreach ($product->productrecipe as $recipe) {
        //     $productreceipe = ProductRecipe::find($recipe->id);
        //     $productreceipe->qty_per_product = round($recipe->qty / $product->yeast, 4);
        //     $productreceipe->save();
        // }




        $notify[] = ['success', 'Product successfully Updated'];
        return to_route('admin.product.index')->withNotify($notify);
    }

    public function destroy(Product $product)
    {
        Gate::authorize('admin.product.destroy');
        ReferenceCommision::where('product_id', $product->id)->delete();
        OrderDetail::where('product_id', $product->id)->delete();
        QuotationDetail::where('product_id', $product->id)->delete();
        ProductRecipe::where('product_id', $product->id)->delete();

        $product->delete();
        $notify[] = ['success', "Product deleted successfully"];
        return back()->withNotify($notify);
    }


    public function status(Request $request, $id)
    {
        Gate::authorize('admin.product.status');
        $product = Product::findOrFail($id);
        if ($product->status == 'Active') {
            $product->status = 'Inactive';
            $notify[] = ['success', 'Product successfully Deactived'];
        } else {
            $product->status = 'Active';
            $notify[] = ['success', 'Product successfully Active'];
        }
        $product->save();
        return back()->withNotify($notify);
    }




    public function customerprice($id)
    {
        Gate::authorize('admin.product.customerprice');
        $data['products'] =  ReferenceCommision::where('product_id', $id)->orderBy('product_id', 'asc')->get();
        return view('admin.products.products.customer_products', $data);
    }

    public function customerpriceupdate(Request $request)
    {
        Gate::authorize('admin.product.customerpriceupdate');

        foreach ($request->invoice_id as $key => $invoice_id) {
            ReferenceCommision::where('id', $invoice_id)->update(['price' => bn2en($request->price[$key]), 'amount' => bn2en($request->amount[$key])]);
        }

        $notify[] = ['success', "Product Price Update successfully"];
        return back()->withNotify($notify);
    }


    public function sales($id)
    {
        $data['orderdetails'] = OrderDetail::where('product_id',$id)->get();
        return view('admin.products.products.sales',$data);
    }


    public function production($id)
    {
        $data['dailyproductions'] = DailyProduction::where('product_id',$id)->get();
        return view('admin.products.products.production',$data);
    }
}
