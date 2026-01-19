<?php

namespace App\Http\Controllers\Admin\Product;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Product\ProductDamage;
use PDF;
class ProductDamageController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.productdamage.list');

        $data['products'] = Product::get();

        $query = ProductDamage::query();

        if ($request->product_id) {
            $data['product_id']        = $request->product_id;
            $query->where('product_id', $request->product_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(40));
        }

        $data['productdamages'] = $query->latest()->paginate(100);


        if ($request->has('search')) {
            return view('admin.orders.productdamages.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.orders.productdamages.product_damage_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {

        } else {
            return view('admin.orders.productdamages.view', $data);
        }




    }

    public function create()
    {
        Gate::authorize('admin.productdamage.create');
        return view('admin.orders.productdamages.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.productdamage.store');
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        ProductDamage::create(array_merge($request->all(), [
            'qty'       => bn2en($request->qty),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Product Damage successfully Added'];
        return to_route('admin.productdamage.index')->withNotify($notify);
    }

    public function show(ProductDamage $productdamage)
    {
        Gate::authorize('admin.productdamage.show');
        return view('admin.ProductDamage.show', compact('productdamage'));
    }

    public function edit(ProductDamage $productdamage)
    {
        Gate::authorize('admin.productdamage.edit');
        return view('admin.orders.productdamages.edit', compact('productdamage'));
    }

    public function update(Request $request, ProductDamage $productdamage)
    {
        Gate::authorize('admin.productdamage.update');
        $request->validate([
            'product_id' => 'required',
            'qty'        => 'required',
        ]);

        $productdamage->update(array_merge($request->all(), [
            'qty'      => bn2en($request->qty),
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Product Damage successfully Updated'];
        return to_route('admin.productdamage.index')->withNotify($notify);
    }

    public function destroy(ProductDamage $productdamage)
    {
        Gate::authorize('admin.productdamage.destroy');

        $productdamage->delete();
        $notify[] = ['success', "Product Damage deleted successfully"];
        return back()->withNotify($notify);
    }
}
