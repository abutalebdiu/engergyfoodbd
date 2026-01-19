<?php

namespace App\Http\Controllers\Admin\Item;

use App\Models\ItemBrand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ItemBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('admin.itembrand.list');
        $data['brands'] = ItemBrand::all();
        return view('admin.items.brand.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        Gate::authorize('admin.itembrand.create');
        if (isset($id)) {
            $data['title'] = 'Edit Brand';
            $data['brand'] = ItemBrand::find($id);
        } else {
            $data['brand'] = null;
            $data['title'] = 'Add Brand';
        }

        return view('admin.items.brand.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null)
    {
        Gate::authorize('admin.itembrand.store');
        if (isset($id)) {
            $brand = ItemBrand::find($id);
            $message = 'Brand updated successfully';
        } else {
            $brand = new ItemBrand();
            $message = 'Brand added successfully';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $brand->name = $request->name;
        $brand->save();

        $notify[] = $message;

        return redirect()->route('admin.items.itemBrand.index')->withNotify($notify);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('admin.itembrand.show');
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('admin.itembrand.edit');
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('admin.itembrand.update');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admin.itembrand.destroy');
        $brand = ItemBrand::find($id);
        $brand->delete();
        $notify[] = ['success', 'Brand deleted successfully'];
        return redirect()->route('admin.items.itemBrand.index')->withNotify($notify);
    }
}
