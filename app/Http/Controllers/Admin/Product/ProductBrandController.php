<?php

namespace App\Http\Controllers\Admin\Product;
use App\Http\Controllers\Controller;

use App\Models\Product\ProductBrand;
use Illuminate\Http\Request;


class ProductBrandController extends Controller
{

    public function index()
    {
        $data['brands'] = ProductBrand::active()->get();
        return view('admin.products.brands.view',$data);
    }

    public function create()
    {
        return view('admin.products.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        ProductBrand::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Product Brand successfully Added'];
        return to_route('admin.productbrand.index')->withNotify($notify);
    }

    public function show(ProductBrand $productbrand)
    {
        return view('admin.products.brands.show',compact('productbrand'));
    }

    public function edit(ProductBrand $productbrand)
    {
        return view('admin.products.brands.edit',compact('productbrand'));
    }

    public function update(Request $request, ProductBrand $productbrand)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $productbrand->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Product Brand successfully Updated'];
        return to_route('admin.productbrand.index')->withNotify($notify);
    }

    public function destroy(ProductBrand $productbrand)
    {
        $productbrand->delete();
        $notify[] = ['success', "Product Brand deleted successfully"];
        return back()->withNotify($notify);
    }
}
