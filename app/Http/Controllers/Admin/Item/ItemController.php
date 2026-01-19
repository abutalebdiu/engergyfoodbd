<?php

namespace App\Http\Controllers\Admin\Item;

use PDF;
use App\Models\Item;
use App\Models\Unit;
use App\Models\ItemBrand;
use App\Exports\ItemExport;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.item.list');
        $data['categories'] = ItemCategory::get();

        $query = Item::query();

        if($request->item_category_id)
        {
            $query->where('item_category_id',$request->item_category_id);
        }
        // else{
        //     $query->where('item_category_id',1);
        // }

        $data['itemsgroupes'] = $query->orderby('name', 'asc')->with('category')->get()->groupby('item_category_id');

        if($request->ajax())
        {
            return response()->json([
                "status" => true,
                "message" => "Data show successfully!",
                "html" => view('admin.items.items.inc.__item_table', $data)->render()
            ], 200);
        }

        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.items.items.index_pdf', $data);
            return $pdf->stream('items_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new ItemExport($data), 'Item_list.xlsx');
        } else {
            return view('admin.items.items.index', $data);
        }
    }

    public function create($id = null)
    {
        Gate::authorize('admin.item.create');
        if (isset($id)) {
            $data['title'] = 'Edit Item';
            $data['item'] = Item::find($id);
        } else {
            $data['title'] = 'Add Item';
        }

        $data['units'] = Unit::get();
        $data['brands'] = ItemBrand::get();
        $data['categories'] = ItemCategory::get();

        return view('admin.items.items.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null)
    {
        Gate::authorize('admin.item.store');
        $required = '';
        if (isset($id)) {
            $item = Item::find($id);
            $item->opening_qty  = bn2en($request->opening_qty);
            $item->qty          = bn2en($request->opening_qty);
            $required           = 'required';
            $message            = 'Item updated successfully';
        } else {
            $item = new Item();
            $item->opening_qty  = bn2en($request->opening_qty);
            $item->qty          = bn2en($request->opening_qty);
            $message            = 'Item created successfully';
        }

        $validator = Validator::make($request->all(), [
            "name"      => "required",
            "unit_id"   => "required|exists:units,id",
            "price"     => "required",
            "qty"       => "nullable",
            "image"     => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        if ($validator->fails()) {

            if ($request->ajax()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors()->first(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $item->name             = $request->name;
        $item->unit_id          = $request->unit_id;
        $item->weight_gram      = $request->weight_gram;
        $item->item_category_id = $request->item_category_id;
        $item->price            = bn2en($request->price);
        $item->description      = $request->description;
        $item->save();


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->hashName();

            $path = 'assets/images/items';
            $image->move($path, $name);

            $item->image = $path . '/' . $name;
            $item->save();
        }

        $notify[] = ['success', $message];

        if ($request->ajax()) {
            return response()->json([
                "status" => true,
                "message" => $message,
                "redirect" => route('admin.items.item.index'),
            ], 200);
        }

        return redirect()->route('admin.items.item.index')->withNotify($notify);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('admin.item.show');
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('admin.item.edit');
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('admin.item.update');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admin.item.destroy');
        $item = Item::find($id);

       // $item->delete();

        return redirect()->route('admin.items.item.index')->with('success', 'Item deleted successfully');
    }
}
