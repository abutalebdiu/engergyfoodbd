<?php

namespace App\Http\Controllers\Admin\Product;

use PDF;

use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Models\Setting\Year;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductStock;
use Illuminate\Support\Facades\Gate;

class ProductStockController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.productstock.list');

        $data['months'] = Month::get();
        $data['years']  = Year::get();
 
        $query = ProductStock::query();


        if ($request->month_id) {
            $query->where('month_id', $request->month_id);
        }
        else{
             $query->where('month_id', Date('m'));
        }
        
        if ($request->year) {
            $query->where('year', $request->year);
        }
        else{
             $query->where('year', Date('Y'));
        }

        $data['productstocks'] = $query->get();

        if ($request->has('search')) {
            return view('admin.orders.productstocks.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.productstocks.product_stock_export', $data);
            return $pdf->stream('product_stock_list.pdf');
        } else {
            return view('admin.orders.productstocks.view', $data);
        }
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.productstock.create');
        $data['months'] = Month::get();
        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');


        if ($request->type == "pdf") {
            $pdf =  PDF::loadView('admin.orders.productstocks.product_stock_pdf', $data);
            return $pdf->stream('product_stock_list.pdf');
        } else {
            return view('admin.orders.productstocks.create', $data);
        }
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.productstock.store');

        $request->validate([
            'date'       => 'required',
            'month_id'   => 'required',
            'product_id' => 'required|array',
            'physical_stock' => 'required|array'
        ]);

        if ($request->product_id && $request->physical_stock) {
            foreach ($request->product_id as $key => $product_id) {
                $product = Product::find($product_id);
                ProductStock::create([
                    'date'              => $request->date,
                    'month_id'          => $request->month_id,
                    'year'              => Date('Y'),
                    'product_id'        => $product_id,
                    'stock_id'          => 1,
                    'last_month_stock'  =>  $product->getopeningstock($product->id),
                    'production'        =>  $product->getproductionvalue($product->id),
                    'sales'             =>  $product->getsalevalue($product->id),
                    'order_return'      =>  $product->getcustomerorderreturn($product->id),
                    'damage'            =>  $product->getproductdamage($product->id),
                    'customer_damage'   =>  $product->getcustomerproductdamage($product->id),
                    'current_stock'     =>  $product->getStock($product->id),
                    'physical_stock'    =>  bn2en($request->physical_stock[$key]),
                    'qty'               => ($product->getStock($product->id) - bn2en($request->physical_stock[$key])),
                    'total'             => ($product->getStock($product->id) - bn2en($request->physical_stock[$key])),
                    'total_value'       => (($product->getStock($product->id) - bn2en($request->physical_stock[$key])) * $product->sale_price),
                    'status'            => 'Minus',
                    'entry_id'          => auth('admin')->user()->id,
                    'type'              => 'settlement'
                ]);

                $product = Product::find($product_id);
                $product->qty = ($product->getStock($product->id) - bn2en($request->physical_stock[$key]));
                $product->save();
            }
        }

        $notify[] = ['success', 'Product Stock successfully Added'];
        return to_route('admin.productstock.index')->withNotify($notify);
    }

    public function show(ProductStock $productstock)
    {
        Gate::authorize('admin.productstock.show');
        return view('admin.orders.productstocks.show', compact('productstock'));
    }

    public function edit(ProductStock $productstock)
    {
        Gate::authorize('admin.productstock.edit');
        return view('admin.orders.productstocks.edit', compact('productstock'));
    }

    public function update(Request $request, ProductStock $productstock)
    {
        $request->validate([
            'physical_stock' => 'required'
        ]);

        $productstock->physical_stock = $request->physical_stock;
        $productstock->save();

        $product = Product::find($productstock->product_id);
        $product->qty = ($product->getStock($product->id) - bn2en($request->physical_stock));
        $product->save();

        $notify[] = ['success', 'ProductStock successfully Updated'];
        return to_route('admin.productstock.index')->withNotify($notify);
    }

    public function destroy(ProductStock $productstock)
    {
        Gate::authorize('admin.productstock.destroy');
        $productstock->delete();
        $notify[] = ['success', "Product Stock deleted successfully"];
        return back()->withNotify($notify);
    }
}
