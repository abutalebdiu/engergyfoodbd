<?php

namespace App\Http\Controllers\Admin\Order;

use Illuminate\Http\Request;
use App\Models\Order\OrderDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;


class OrderDetailController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.orderdetail.list');

      $data['orderdetails'] = OrderDetail::whereNotIn('order_id', function ($query) {
                                        $query->select('id')->from('orders');
                                    })
                                    ->latest()
                                    ->paginate(200);

        return view('admin.orders.orderdetails.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.orderdetail.create');
        return view('admin.OrderDetail.create');
    }

    public function store(Request $request)
    {

        Gate::authorize('admin.orderdetail.store');

        $request->validate([
            'name' => 'required',
        ]);

        OrderDetail::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'OrderDetail successfully Added'];
        return redirect('admin.OrderDetail.index')->withNotify($notify);
    }

    public function show(OrderDetail $orderDetail)
    {
        Gate::authorize('admin.orderdetail.show');

        return view('admin.OrderDetail.show', compact('orderDetail'));
    }

    public function edit(OrderDetail $orderDetail)
    {
        Gate::authorize('admin.orderdetail.edit');
        return view('admin.OrderDetail.edit', compact('orderDetail'));
    }

    public function update(Request $request, OrderDetail $orderDetail)
    {
        Gate::authorize('admin.orderdetail.update');
        $request->validate([
            'name' => 'required',
        ]);

        $orderDetail->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'OrderDetail successfully Updated'];
        return to_route('admin.OrderDetail.index')->withNotify($notify);
    }

    public function destroy(OrderDetail $orderDetail)
    {
        Gate::authorize('admin.orderdetail.destroy');
        $orderDetail->delete();
        $notify[] = ['success', "OrderDetail deleted successfully"];
        return back()->withNotify($notify);
    }
}
