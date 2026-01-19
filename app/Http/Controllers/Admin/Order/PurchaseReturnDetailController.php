<?php

namespace App\Http\Controllers\Admin\Order;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Order\PurchaseReturnDetail;

class PurchaseReturnDetailController extends Controller
{

    public function index()
    {
        Gate::authorize('app.purchasereturndetail.list');
        $data['PurchaseReturnDetails'] = PurchaseReturnDetail::active()->get();

        return view('admin.PurchaseReturnDetail.view', $data);
    }

    public function create()
    {
        Gate::authorize('app.purchasereturndetail.create');
        return view('admin.PurchaseReturnDetail.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('app.purchasereturndetail.store');
        $request->validate([
            'name' => 'required',
        ]);

        PurchaseReturnDetail::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'PurchaseReturnDetail successfully Added'];
        return to_route('admin.PurchaseReturnDetail.index')->withNotify($notify);
    }

    public function show(PurchaseReturnDetail $purchaseReturnDetail)
    {
        Gate::authorize('app.purchasereturndetail.show');
        return view('admin.PurchaseReturnDetail.show', compact('purchaseReturnDetail'));
    }

    public function edit(PurchaseReturnDetail $purchaseReturnDetail)
    {
        Gate::authorize('app.purchasereturndetail.edit');
        return view('admin.PurchaseReturnDetail.edit', compact('purchaseReturnDetail'));
    }

    public function update(Request $request, PurchaseReturnDetail $purchaseReturnDetail)
    {
        Gate::authorize('app.purchasereturndetail.update');
        $request->validate([
            'name' => 'required',
        ]);

        $purchaseReturnDetail->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'PurchaseReturnDetail successfully Updated'];
        return to_route('admin.PurchaseReturnDetail.index')->withNotify($notify);
    }

    public function destroy(PurchaseReturnDetail $purchaseReturnDetail)
    {
        Gate::authorize('app.purchasereturndetail.destroy');
        $purchaseReturnDetail->delete();
        $notify[] = ['success', "PurchaseReturnDetail deleted successfully"];
        return back()->withNotify($notify);
    }
}
