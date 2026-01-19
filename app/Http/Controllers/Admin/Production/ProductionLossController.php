<?php

namespace App\Http\Controllers\Admin\Production;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\ProductionLoss;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class ProductionLossController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.productionloss.list');

        $data['productions'] = ProductionLoss::get();
        return view('admin.productions.productionslosses.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.productionloss.create');

        $data['items']          = Item::get();
        $data['departments']    = Department::whereBetween('id', [2, 10])->get();
        return view('admin.productions.productionslosses.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.productionloss.store');

        $request->validate([
            'item_id'       => 'required',
            'date'          => 'required',
            'department_id' => 'required',
            'qty'           => 'required',
        ]);

        ProductionLoss::create(array_merge($request->all(), [
            'qty' => bn2en($request->qty),
            'entry_id'  => auth('admin')->user()->id
        ]));

        $product = Item::findOrFail($request->item_id);
        $product->qty -= bn2en($request->qty);
        $product->save();


        Session::put('department_id', $request->department_id);

        $notify[] = ['success', 'successfully Added'];
        return to_route('admin.productionloss.index')->withNotify($notify);
    }

    public function show(ProductionLoss $productionloss)
    {
        Gate::authorize('admin.productionloss.show');

        return view('admin.productions.productionslosses.show', compact('productionloss'));
    }

    public function edit(ProductionLoss $productionloss)
    {
        Gate::authorize('admin.productionloss.edit');

        $data['items']          = Item::get();
        $data['departments']    = Department::whereBetween('id', [2, 10])->get();
        return view('admin.productions.productionslosses.edit', compact('productionloss'), $data);
    }

    public function update(Request $request, ProductionLoss $productionloss)
    {
        Gate::authorize('admin.productionloss.update');
        $request->validate([
            'item_id'       => 'required',
            'date'          => 'required',
            'department_id' => 'required',
            'qty'           => 'required',
        ]);

        $productionloss->update(array_merge($request->all(), [
            'qty' => bn2en($request->qty),
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $product = Item::findOrFail($request->item_id);
        $product->qty -=  bn2en($request->qty);
        $product->save();

        $notify[] = ['success', 'Successfully Updated'];
        return to_route('admin.productionloss.index')->withNotify($notify);
    }

    public function destroy(ProductionLoss $productionloss)
    {
        Gate::authorize('admin.productionloss.destroy');

        $productionloss->delete();

        $notify[] = ['success', "Successfully Deleted"];
        return back()->withNotify($notify);
    }
}
