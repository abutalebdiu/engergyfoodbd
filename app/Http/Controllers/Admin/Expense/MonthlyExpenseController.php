<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Models\Expense\MonthlyExpense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MonthlyExpenseController extends Controller
{

    public function index()
    {
        $data['monthlyexpenses'] = MonthlyExpense::active()->get();

        return view('admin.expenses.monthlyexpenses.view', $data);
    }

    public function create()
    {
        return view('admin.expenses.monthlyexpenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        MonthlyExpense::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'MonthlyExpense successfully Added'];
        return to_route('admin.monthlyexpense.index')->withNotify($notify);
    }

    public function show(MonthlyExpense $monthlyExpense)
    {
        return view('admin.expenses.monthlyexpenses.show', compact('monthlyExpense'));
    }

    public function edit($id)
    {
        $monthlyExpense = MonthlyExpense::findOrFail($id);
        return view('admin.expenses.monthlyexpenses.edit', compact('monthlyExpense'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $monthlyExpense = MonthlyExpense::findOrFail($id);

        $monthlyExpense->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'MonthlyExpense successfully Updated'];
        return to_route('admin.monthlyexpense.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $monthlyExpense = MonthlyExpense::findOrFail($id);
        $monthlyExpense->delete();
        $notify[] = ['success', "MonthlyExpense deleted successfully"];
        return back()->withNotify($notify);
    }
}
