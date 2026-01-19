<?php

namespace App\Http\Controllers\Admin\Order;


use Illuminate\Http\Request;
use App\Models\Order\Purchse;
use App\Http\Controllers\Controller;
use App\Models\Order\PurchaseReturn;
use Illuminate\Support\Facades\Gate;
use App\Models\Order\PurchaseReturnDetail;

class PurchaseReturnController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.purchasereturn.list');

        $data['purchasereturns'] = PurchaseReturn::get();
        return view('admin.orders.purchasereturns.view', $data);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.purchasereturn.create');
        $data['purchase'] = Purchse::find($request->purchase_id);
        return view('admin.orders.purchasereturns.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.purchasereturn.store');

        $purchasereturn = new PurchaseReturn();
        $purchasereturn->purchase_id = $request->purchase_id;
        $purchasereturn->supplier_id = $request->supplier_id;
        $purchasereturn->amount      = $request->total_amount;
        $purchasereturn->payment_status = 'Unpaid';
        $purchasereturn->entry_id   = auth('admin')->user()->id;
        $purchasereturn->save();


        $input = $request->all();


        foreach ($input['purchase_detail_id'] as $key => $val) {
            $returndetail = new PurchaseReturnDetail();
            $returndetail->purchase_return_id = $purchasereturn->id;
            $returndetail->purchase_detail_id = $input['purchase_detail_id'][$key];
            $returndetail->product_id = $input['product_id'][$key];
            $returndetail->price = $input['price'][$key];
            $returndetail->qty = $input['qty'][$key];
            $returndetail->amount = $input['amount'][$key];
            $returndetail->entry_id = auth('admin')->user()->id;
            $returndetail->save();
        }


        $notify[] = ['success', 'Purchase Return successfully Added'];
        return to_route('admin.purchasereturn.index')->withNotify($notify);
    }

    public function show(PurchaseReturn $purchasereturn)
    {
        Gate::authorize('admin.purchasereturn.show');
        return view('admin.orders.purchasereturns.show', compact('purchasereturn'));
    }

    public function edit(PurchaseReturn $purchasereturn)
    {
        Gate::authorize('admin.purchasereturn.edit');
        return view('admin.orders.purchasereturns.edit', compact('purchasereturn'));
    }

    public function update(Request $request, PurchaseReturn $purchasereturn)
    {
        Gate::authorize('admin.purchasereturn.update');
        $request->validate([
            'name' => 'required',
        ]);

        $purchasereturn->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Purchase Return successfully Updated'];
        return to_route('admin.purchasereturn.index')->withNotify($notify);
    }

    public function destroy(PurchaseReturn $purchasereturn)
    {
        Gate::authorize('admin.purchasereturn.destroy');
        $purchasereturn->delete();
        $notify[] = ['success', "Purchase Return deleted successfully"];
        return back()->withNotify($notify);
    }
}
