<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Models\Expense\AssetExpense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AssetExpenseController extends Controller
{

    public function index()
    {
        $data['assetexpenses'] = AssetExpense::active()->get();
        return view('admin.expenses.assetexpenses.view', $data);
    }

    public function create()
    {
        return view('admin.expenses.assetexpenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'description'   => 'nullable',
        ]);

        AssetExpense::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Asset Expense successfully Added'];
        return to_route('admin.assetexpense.index')->withNotify($notify);
    }

    public function show(AssetExpense $assetexpense)
    {
        return view('admin.expenses.assetexpenses.show', compact('assetexpense'));
    }

    public function edit($id)
    {
        $assetexpense = AssetExpense::findOrFail($id);
        return view('admin.expenses.assetexpenses.edit', compact('assetexpense'));
    }

    public function update(Request $request,AssetExpense $assetexpense)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $assetexpense->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Asset Expense successfully Updated'];
        return to_route('admin.assetexpense.index')->withNotify($notify);
    }

    public function destroy(AssetExpense $assetexpense)
    {
        $assetexpense->delete();

        $notify[] = ['success', "Asset Expense deleted successfully"];
        return back()->withNotify($notify);
    }
}
