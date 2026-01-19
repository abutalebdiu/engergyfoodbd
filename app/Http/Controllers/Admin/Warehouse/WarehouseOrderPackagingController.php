<?php

namespace App\Http\Controllers\Admin\Warehouse;
use App\Http\Controllers\Controller;
use App\Models\WarehouseOrderPackaging;
use Illuminate\Http\Request;


class WarehouseOrderPackagingController extends Controller
{

    public function index()
    {
        $data['WarehouseOrderPackagings'] = WarehouseOrderPackaging::active()->get();

        return view('admin.WarehouseOrderPackaging.view',$data);
    }

    public function create()
    {
        return view('admin.WarehouseOrderPackaging.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        WarehouseOrderPackaging::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'WarehouseOrderPackaging successfully Added'];
        return redirect('admin.WarehouseOrderPackaging.index')->withNotify($notify);
    }

    public function show(WarehouseOrderPackaging $warehouseOrderPackaging)
    {
         return view('admin.WarehouseOrderPackaging.show',compact('warehouseOrderPackaging'));
    }

    public function edit(WarehouseOrderPackaging $warehouseOrderPackaging)
    {
        return view('admin.WarehouseOrderPackaging.edit',compact('warehouseOrderPackaging'));
    }

    public function update(Request $request, WarehouseOrderPackaging $warehouseOrderPackaging)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $warehouseOrderPackaging->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'WarehouseOrderPackaging successfully Updated'];
        return to_route('admin.WarehouseOrderPackaging.index')->withNotify($notify);
    }

    public function destroy(WarehouseOrderPackaging $warehouseOrderPackaging)
    {
        $warehouseOrderPackaging->delete();
        $notify[] = ['success', "WarehouseOrderPackaging deleted successfully"];
        return back()->withNotify($notify);
    }
}
