<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductCategoryController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.productcategory.list');
        $data['categories'] = ProductCategory::active()->get();
        return view('admin.products.categories.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.productcategory.create');
        return view('admin.products.categories.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.productcategory.store');
        $request->validate([
            'name' => 'required',
        ]);

        ProductCategory::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Product Category successfully Added'];
        return to_route('admin.productcategory.index')->withNotify($notify);
    }

    public function show(ProductCategory $productcategory)
    {
        Gate::authorize('admin.productcategory.show');
        return view('admin.products.categories.show', compact('productcategory'));
    }

    public function edit(ProductCategory $productcategory)
    {
        Gate::authorize('admin.productcategory.edit');
        return view('admin.products.categories.edit', compact('productcategory'));
    }

    public function update(Request $request, ProductCategory $productcategory)
    {
        Gate::authorize('admin.productcategory.update');
        $request->validate([
            'name' => 'required',
        ]);

        $productcategory->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Product Category successfully Updated'];
        return to_route('admin.productcategory.index')->withNotify($notify);
    }

    public function destroy(ProductCategory $productcategory)
    {
        Gate::authorize('admin.productcategory.destroy');
        $productcategory->delete();
        $notify[] = ['success', "Product Category deleted successfully"];
        return back()->withNotify($notify);
    }
}
