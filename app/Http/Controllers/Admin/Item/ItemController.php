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

        $data['categories'] = cache()->remember('item_categories', 86400, function() {
            return ItemCategory::select('id', 'name')->get();
        });

        $query = Item::query()
            ->select('items.*')
            ->with('category:id,name')
            ->with('unit:id,name')
            ->orderBy('items.name', 'asc');


        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('item_category_id')) {
            $query->where('item_category_id', $request->item_category_id);
        }

        if ($request->ajax()) {
            $perPage = $request->get('per_page', 10);
            $items = $query->paginate($perPage);
            $data['itemsgroupes'] = $items->groupBy('item_category_id');
            $data['pagination'] = $items;

            return response()->json([
                "status" => true,
                "message" => "Data show successfully!",
                "html" => view('admin.items.items.inc.__item_table', $data)->render(),
                "pagination" => view('admin.items.items.inc.__pagination', $data)->render()
            ], 200);
        }

        if ($request->has('pdf')) {
            $data['itemsgroupes'] = $query->get()->groupBy('item_category_id');
            $pdf = PDF::loadView('admin.items.items.index_pdf', $data);
            return $pdf->stream('items_list.pdf');
        }

        if ($request->has('excel')) {
            $data['itemsgroupes'] = $query->get()->groupBy('item_category_id');
            return Excel::download(new ItemExport($data), 'Item_list.xlsx');
        }

        $perPage = $request->get('per_page', 15);
        $items = $query->paginate($perPage);
        $data['itemsgroupes'] = $items->groupBy('item_category_id');
        $data['pagination'] = $items;

        return view('admin.items.items.index', $data);
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
        $item->entry_id         = auth('admin')->user()->id;
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
