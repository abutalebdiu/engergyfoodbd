<?php

namespace App\Http\Controllers\Admin\Expense;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense\ExpenseCategory;
use Illuminate\Support\Facades\Gate;

class ExpenseCategoryController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.expensecategory.list');

        $data['expensecategories'] = ExpenseCategory::active()->get();
        return view('admin.expenses.expensecategories.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.expensecategory.create');
        return view('admin.expenses.expensecategories.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.expensecategory.store');
        $request->validate([
            'name' => 'required',
        ]);

        ExpenseCategory::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'Expense Category successfully Added'];
        return to_route('admin.expensecategory.index')->withNotify($notify);
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        Gate::authorize('admin.expensecategory.show');
        return view('admin.expenses.expensecategories.show', compact('expenseCategory'));
    }

    public function edit(ExpenseCategory $expensecategory)
    {
        Gate::authorize('admin.expensecategory.edit');
        return view('admin.expenses.expensecategories.edit', compact('expensecategory'));
    }

    public function update(Request $request, ExpenseCategory $expensecategory)
    {
        Gate::authorize('admin.expensecategory.update');
        $request->validate([
            'name' => 'required',
        ]);

        $expensecategory->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'ExpenseCategory successfully Updated'];
        return to_route('admin.expensecategory.index')->withNotify($notify);
    }

    public function destroy(ExpenseCategory $expensecategory)
    {
        Gate::authorize('admin.expensecategory.destroy');
        $expensecategory->delete();
        $notify[] = ['success', "Expense Category  successfully Deleted"];
        return back()->withNotify($notify);
    }
}
