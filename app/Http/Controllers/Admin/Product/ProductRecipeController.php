<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductRecipe;

class ProductRecipeController extends Controller
{

    public function index()
    {
        $data['ProductRecipes'] = ProductRecipe::active()->get();

        return view('admin.ProductRecipe.view', $data);
    }

    public function create()
    {
        return view('admin.ProductRecipe.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'item_id'    => 'required',
        ]);

        $productrecipe = new ProductRecipe();

        $productrecipe->product_id  = $request->product_id;
        $productrecipe->item_id     = $request->item_id;
        $productrecipe->unit_id     = $request->unit_id;
        $productrecipe->qty         = bn2en($request->qty);
        $productrecipe->save();


        $product = Product::Find($request->product_id);

        foreach ($product->productrecipe as $recipe) {
            $productreceipe = ProductRecipe::find($recipe->id);
            $productreceipe->qty_per_product = round($recipe->qty / $product->yeast, 4);
            $productreceipe->save();
        }


        $notify[] = ['success', 'Product Recipe successfully Added'];
        return back()->withNotify($notify);
    }

    public function show(ProductRecipe $productRecipe)
    {
        return view('admin.ProductRecipe.show', compact('productRecipe'));
    }

    public function edit(ProductRecipe $productRecipe)
    {
        return view('admin.ProductRecipe.edit', compact('productRecipe'));
    }

    public function update(Request $request, ProductRecipe $productrecipe)
    {
        $request->validate([
            'product_id'    => 'required',
            'unit_id'       => 'required',
        ]);

        $productrecipe->item_id  = $request->item_id;
        $productrecipe->unit_id     = $request->unit_id;
        $productrecipe->qty         = bn2en($request->qty);
        $productrecipe->save();


        // foreach (Product::get() as $product) {
        //     foreach ($product->productrecipe as $recipe) {
        //         $productreceipe = ProductRecipe::find($recipe->id);
        //         $productreceipe->qty_per_product = round($recipe->qty / $product->yeast, 4);
        //         $productreceipe->save();
        //     }
        // }

        $product = Product::Find($productrecipe->product_id);

        foreach ($product->productrecipe as $recipe) {
            $productreceipe = ProductRecipe::find($recipe->id);
            $productreceipe->qty_per_product = round($recipe->qty / $product->yeast, 4);
            $productreceipe->save();
        }

        $notify[] = ['success', 'Product Recipe successfully Updated'];
        return back()->withNotify($notify);
    }

    public function destroy(ProductRecipe $productrecipe)
    {
        $productrecipe->delete();
        $notify[] = ['success', "Product Recipe deleted successfully"];
        return back()->withNotify($notify);
    }
}
