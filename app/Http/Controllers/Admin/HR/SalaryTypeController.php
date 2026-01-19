<?php

namespace App\Http\Controllers\Admin\HR;

use Illuminate\Http\Request;
use App\Models\HR\SalaryType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SalaryTypeController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.salarytype.list');

        $data['salarytypes'] = SalaryType::active()->get();
        return view('admin.hr.salarytypes.index', $data);
    }

    public function create()
    {
        Gate::authorize('admin.salarytype.create');
        return view('admin.hr.salarytypes.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.salarytype.store');
        $request->validate([
            'name' => 'required'
        ]);

        SalaryType::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Salary Type successfully Added'];
        return to_route('admin.salarytype.index')->withNotify($notify);
    }

    public function show(SalaryType $salarytype)
    {
        Gate::authorize('admin.salarytype.show');
        return view('admin.hr.salarytypes.show', compact('salaryType'));
    }

    public function edit(SalaryType $salarytype)
    {
        Gate::authorize('admin.salarytype.edit');
        return view('admin.hr.salarytypes.edit', compact('salarytype'));
    }

    public function update(Request $request, SalaryType $salarytype)
    {
        Gate::authorize('admin.salarytype.update');
        $request->validate([
            'name' => 'required',
        ]);

        $salarytype->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Salary Type successfully Updated'];
        return to_route('admin.salarytype.index')->withNotify($notify);
    }

    public function destroy(SalaryType $salarytype)
    {
        Gate::authorize('admin.salarytype.destroy');
        $salarytype->delete();
        $notify[] = ['success', "Salary Type deleted successfully"];
        return back()->withNotify($notify);
    }
}
