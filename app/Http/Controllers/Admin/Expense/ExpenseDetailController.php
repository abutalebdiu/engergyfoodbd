<?php

namespace App\Http\Controllers\Admin\Expense;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Expense\ExpenseDetail;

class ExpenseDetailController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.expensedetail.list');

        $data['ExpenseDetails'] = ExpenseDetail::active()->get();

        return view('admin.ExpenseDetail.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.expensedetail.create');
        return view('admin.ExpenseDetail.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.expensedetail.store');
        $request->validate([
            'name' => 'required',
        ]);

        ExpenseDetail::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'ExpenseDetail successfully Added'];
        return redirect('admin.ExpenseDetail.index')->withNotify($notify);
    }

    public function show(ExpenseDetail $expenseDetail)
    {
        Gate::authorize('admin.expensedetail.show');
        return view('admin.ExpenseDetail.show', compact('expenseDetail'));
    }

    public function edit(ExpenseDetail $expenseDetail)
    {
        Gate::authorize('admin.expensedetail.edit');
        return view('admin.ExpenseDetail.edit', compact('expenseDetail'));
    }

    public function update(Request $request, ExpenseDetail $expenseDetail)
    {
        Gate::authorize('admin.expensedetail.update');
        $request->validate([
            'name' => 'required',
        ]);

        $expenseDetail->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'ExpenseDetail successfully Updated'];
        return to_route('admin.ExpenseDetail.index')->withNotify($notify);
    }

    public function destroy(ExpenseDetail $expenseDetail)
    {
        Gate::authorize('admin.expensedetail.destroy');
        $expenseDetail->delete();
        $notify[] = ['success', "ExpenseDetail deleted successfully"];
        return back()->withNotify($notify);
    }
}
