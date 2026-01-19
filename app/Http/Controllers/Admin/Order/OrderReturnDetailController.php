<?php

namespace App\Http\Controllers\Admin\Order;

 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\OrderReturnDetail;

class OrderReturnDetailController extends Controller
{

    public function index()
    {
        $data['OrderReturnDetails'] = OrderReturnDetail::active()->get();

        return view('admin.OrderReturnDetail.view',$data);
    }

    public function create()
    {
        return view('admin.OrderReturnDetail.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        OrderReturnDetail::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'OrderReturnDetail successfully Added'];
        return to_route('admin.OrderReturnDetail.index')->withNotify($notify);
    }

    public function show(OrderReturnDetail $orderReturnDetail)
    {
         return view('admin.OrderReturnDetail.show',compact('orderReturnDetail'));
    }

    public function edit(OrderReturnDetail $orderReturnDetail)
    {
        return view('admin.OrderReturnDetail.edit',compact('orderReturnDetail'));
    }

    public function update(Request $request, OrderReturnDetail $orderReturnDetail)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $orderReturnDetail->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'OrderReturnDetail successfully Updated'];
        return to_route('admin.OrderReturnDetail.index')->withNotify($notify);
    }

    public function destroy(OrderReturnDetail $orderReturnDetail)
    {
        $orderReturnDetail->delete();
        $notify[] = ['success', "OrderReturnDetail deleted successfully"];
        return back()->withNotify($notify);
    }
}
