<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order\OrderDetail;
use App\Http\Controllers\Controller;
use App\Models\Warehouse\WarehouseOrder;


class WarehouseOrderController extends Controller
{

    public function index()
    {
        $data['warehouseorders'] = WarehouseOrder::active()->get();
        return view('admin.warehouses.warehouseorders.view', $data);
    }

    public function create()
    {
        $data['suppliers'] = User::active()->where('type', 'supplier')->get();
        return view('admin.warehouses.warehouseorders.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_detail_id' => 'required',
            'supplier_id' => 'required',
            'date' => 'required',
            'order_qty' => 'required',
            'receive_qty' => 'required',
        ]);

        $orderdetail = OrderDetail::find($request->order_detail_id);

        WarehouseOrder::create(array_merge($request->except('order_qty','receive_qty'), [
            'buyer_id'  => optional($orderdetail->order)->buyer_id,
            'order_id'  => $orderdetail->order_id,
            'entry_id'  => auth('admin')->user()->id,
            'date'      => now(),
            'qty'       => $request->receive_qty, 
            'status'    => 'Active'
        ]));

        $orderdetail->receive_qty = $orderdetail->receive_qty + $request->receive_qty;
        $orderdetail->pending_qty = $orderdetail->qty - $orderdetail->receive_qty + $request->receive_qty;
        $orderdetail->bag         = $request->bag;
        $orderdetail->save();


        $notify[] = ['success', 'Supplier Product successfully Received'];
        return to_route('admin.warehouseorder.index')->withNotify($notify);
    }

    public function show(WarehouseOrder $warehouseorder)
    {
        return view('admin.warehouses.warehouses.show', compact('warehouseorder'));
    }

    public function edit(WarehouseOrder $warehouseorder)
    {
        $data['suppliers'] = User::active()->where('type', 'supplier')->get();
        return view('admin.warehouses.warehouseorders.edit', compact('warehouseorder'), $data);
    }

    public function update(Request $request, WarehouseOrder $warehouseorder)
    {
        $request->validate([
            'order_detail_id' => 'required',
            'supplier_id' => 'required',
            'date' => 'required',
            'order_qty' => 'required',
            'receive_qty' => 'required',
        ]);

        $orderdetail = OrderDetail::find($request->order_detail_id);


        $warehouseorder->update(array_merge($request->all(), [
            'buyer_id' => optional($orderdetail->order)->buyer_id,
            'order_id' => $orderdetail->order_id,
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Supplier Product Received successfully  Updated'];
        return to_route('admin.warehouseorder.index')->withNotify($notify);
    }

    public function destroy(WarehouseOrder $warehouseorder)
    {
        $warehouseorder->delete();
        $notify[] = ['success', "Warehouse Order deleted successfully"];
        return back()->withNotify($notify);
    }
}
