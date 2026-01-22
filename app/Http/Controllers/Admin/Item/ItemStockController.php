<?php

namespace App\Http\Controllers\Admin\Item;

use PDF;
use App\Models\Item;
use App\Models\ItemStock;
use Illuminate\Http\Request;
use App\Models\Setting\Month;
use App\Models\Setting\Year;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemStockController extends Controller
{
    public function index(Request $request)
    {
        // Dropdown data
        $data['months'] = Month::all();
        $data['years']  = Year::all();

        // Base query
        $query = ItemStock::with(['item', 'month']);

        // Month filter (default: 14)
        $query->where('month_id', $request->month_id ?? 14);

        // Year filter (default: current year)
        $query->where('year', $request->year ?? date('Y'));

        // Group by item category (collection level)
        $collection = $query->get()->groupBy('item.item_category_id');

        // Manual pagination for grouped data
        $page    = $request->get('page', 1);
        $perPage = 10;

        $itemstocks = new LengthAwarePaginator(
            $collection
                ->slice(($page - 1) * $perPage, $perPage)
                ->values(), // reset keys
            $collection->count(),
            $perPage,
            $page,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        $data['itemstocks'] = $itemstocks;

        // PDF export
        if ($request->has('pdf')) {
            return PDF::loadView(
                'admin.items.itemstocks.item_stock_export',
                $data
            )->stream('item_stock_list.pdf');
        }

        // Default view (search & normal request)
        return view('admin.items.itemstocks.view', $data);
    }



    public function create(Request $request)
    {
        $data['itemswithcategories'] = Item::where('status', 'Active')->with('category')->get()->groupby('item_category_id');
        $data['months'] = Month::get();

        if ($request->type == "pdf") {
            $pdf =  PDF::loadView('admin.items.itemstocks.item_stock_pdf', $data);
            return $pdf->stream('item_stock_list.pdf');
        } else {
            return view('admin.items.itemstocks.create', $data);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'                  => 'required',
            'month_id'              => 'required',
            'item_id'               => 'required|array',
            'physical_stock'        => 'required|array',
        ]);


        if ($request->item_id && $request->physical_stock) {
            foreach ($request->item_id as $key => $item_id) {
                $item = Item::find($item_id);
                ItemStock::create([
                    'date'              => $request->date,
                    'month_id'          => $request->month_id,
                    'year'              => Date('Y'),
                    'item_id'           => $item_id,
                    'last_month_stock'  => round($item->getopeningstock($item->id), 2),
                    'purchase'          => round($item->getpurchasevalue($item->id), 2),
                    'make_production'   => round($item->getmakeproductionvalue($item->id), 2),
                    'production_loss'   => round($item->productionloss($item->id), 2),
                    'current_stock'     => round($item->stock($item->id), 2),
                    'physical_stock'    => bn2en($request->physical_stock[$key]),
                    'qty'               => round(($item->stock($item->id) - bn2en($request->physical_stock[$key])), 2),
                    'total'             => round($item->stock($item->id) - bn2en($request->physical_stock[$key]), 2),
                    'total_value'       => round((($item->stock($item->id) - bn2en($request->physical_stock[$key])) * $item->price), 2),
                    'stock_id'          => 1,
                    'entry_id'          => auth('admin')->user()->id,
                    'type'              => 'settlement',
                    'status'            => 'Minus'
                ]);

                $item = Item::find($item_id);
                $item->qty = bn2en($request->physical_stock[$key]);
                $item->save();
            }
        }


        $notify[] = ['success', 'Item Stock successfully Added'];
        return to_route('admin.itemtstock.index')->withNotify($notify);
    }

    public function show(ItemStock $itemStock)
    {
        return view('admin.ItemStock.show', compact('itemStock'));
    }

    public function edit($id)
    {
        $itemstock = ItemStock::find($id);
        return view('admin.items.itemstocks.edit',compact('itemstock'));
       // return view('admin.ItemStock.edit', compact('itemstock'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'physical_stock' => 'required'
        ]);

        $itemstock = ItemStock::find($id);
        $itemstock->physical_stock = $request->physical_stock;
        $itemstock->save();

        $item = Item::find($itemstock->item_id);
        $item->qty = ($item->stock($item->id) - bn2en($request->physical_stock));
        $item->save();

        $notify[] = ['success', 'Item Stock successfully Updated'];
        return to_route('admin.itemtstock.index')->withNotify($notify);
    }

    public function destroy(ItemStock $itemStock)
    {
        $itemStock->delete();
        $notify[] = ['success', "ItemStock deleted successfully"];
        return back()->withNotify($notify);
    }
}
