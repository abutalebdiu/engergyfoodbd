<?php

namespace App\Http\Controllers\Admin\Expense;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense\TransportExpense;

class TransportExpenseController extends Controller
{
    public function index()
    {
        $data['transportexpenses'] = TransportExpense::get();
        return view('admin.expenses.transportexpenses.view', $data);
    }


    public function create()
    {
        return view('admin.expenses.transportexpenses.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        TransportExpense::create(array_merge($validatedData, [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));


        $notify[] = ['success', 'Transport Expense successfully Added'];
        return to_route('admin.transportexpense.index')->withNotify($notify);
    }

    public function show($id)
    {
        $transportExpense = TransportExpense::findOrFail($id);
        return view('admin.transportexpenses.show', compact('transportExpense'));
    }

    public function edit(TransportExpense $transportexpense)
    {

        return view('admin.expenses.transportexpenses.edit', compact('transportexpense'));
    }

    public function update(Request $request,TransportExpense  $transportexpense)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $transportexpense->update(array_merge($validatedData, [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Transport Expense successfully Updated'];
        return to_route('admin.transportexpense.index')->withNotify($notify);
    }

    public function destroy(TransportExpense  $transportexpense)
    {
        $transportexpense->delete();

        $notify[] = ['success', "Transport Expense deleted successfully"];
        return back()->withNotify($notify);
    }
}
