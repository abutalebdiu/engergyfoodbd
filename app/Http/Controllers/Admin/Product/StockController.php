<?php

namespace App\Http\Controllers\Admin\Product;
use App\Http\Controllers\Controller;

use App\Models\Product\Stock;
use Illuminate\Http\Request;


class StockController extends Controller
{

    public function index()
    {
        $data['stocks'] = Stock::active()->get();

        return view('admin.products.stocks.view',$data);
    }

    public function create()
    {
        return view('admin.products.stocks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Stock::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Stock successfully Added'];
        return to_route('admin.stock.index')->withNotify($notify);
    }

    public function show(Stock $stock)
    {
        return view('admin.products.stocks.show',compact('stock'));
    }

    public function edit(Stock $stock)
    {
        return view('admin.products.stocks.edit',compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $stock->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Stock successfully Updated'];
        return to_route('admin.stock.index')->withNotify($notify);
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        $notify[] = ['success', "Stock deleted successfully"];
        return back()->withNotify($notify);
    }
}
