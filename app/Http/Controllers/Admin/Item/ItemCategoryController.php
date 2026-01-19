<?php

namespace App\Http\Controllers\Admin\Item;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ItemCategoryController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.itemcategory.list');
        $data['itemcategories'] = ItemCategory::get();
        return view('admin.items.item-category.index', $data);
    }


    public function create($id = null)
    {
        Gate::authorize('admin.itemcategory.create');
        if (isset($id)) {
            $data['title'] = 'Edit Category';
            $data['category'] = ItemCategory::find($id);
        } else {
            $data['title'] = 'Add Category';
        }

        return view('admin.items.item-category.form', $data);
    }


    public function store(Request $request, $id = null)
    {
        Gate::authorize('admin.itemcategory.store');
        if (isset($id)) {
            $category = ItemCategory::find($id);
            $message = 'Category updated successfully';
        } else {
            $category = new ItemCategory();
            $message = 'Category added successfully';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();

        $notify[] = $message;

        return redirect()->route('admin.items.itemCategory.index')->withNotify($notify);
    }


    public function show(string $id)
    {
        Gate::authorize('admin.itemcategory.show');
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('admin.itemcategory.edit');
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('admin.itemcategory.update');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admin.itemcategory.destroy');
        $category = ItemCategory::find($id);
        $category->delete();
        $notify[] = 'Category deleted successfully';
        return redirect()->route('admin.items.itemCategory.index')->withNotify($notify);
    }
}
