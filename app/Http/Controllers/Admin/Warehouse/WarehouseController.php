<?php

namespace App\Http\Controllers\Admin\Warehouse;

use Illuminate\Http\Request;
use App\Models\Warehouse\Warehouse;
use App\Http\Controllers\Controller;


class WarehouseController extends Controller
{

    public function index()
    {
        $data['warehouses'] = Warehouse::active()->get();
        return view('admin.warehouses.warehouses.view', $data);
    }

    public function create()
    {
        return view('admin.warehouses.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Warehouse::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'Warehouse successfully Added'];
        return to_route('admin.warehouse.index')->withNotify($notify);
    }

    public function show(Warehouse $warehouse)
    {
        return view('admin.warehouses.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $warehouse->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Warehouse successfully Updated'];
        return to_route('admin.warehouse.index')->withNotify($notify);
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        $notify[] = ['success', "Warehouse deleted successfully"];
        return back()->withNotify($notify);
    }
}
