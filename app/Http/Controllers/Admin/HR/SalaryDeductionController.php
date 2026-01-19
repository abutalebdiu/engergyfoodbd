<?php

namespace App\Http\Controllers\Admin\HR;


use App\Models\HR\Employee;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Setting\Month;

use App\Models\HR\SalaryDeduction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SalaryDeductionController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.salarydeduction.list');

        $data['salarydeductions'] = SalaryDeduction::latest()->get();
        return view('admin.hr.salarydeductions.view', $data);
    }


    public function create()
    {
        Gate::authorize('admin.salarydeduction.create');
        $data['months'] = Month::get();
        $data['years'] = Year::latest()->get();
        $data['departments']    = Department::orderBy('position', 'ASC')->get();
        return view('admin.hr.salarydeductions.create', $data);
    }


    public function store(Request $request)
    {
        Gate::authorize('admin.salarydeduction.store');
        $request->validate([
            'month_id'      => 'required',
            'employee_id'   => 'required',
            'amount'        => 'required'
        ]);

        SalaryDeduction::create(array_merge($request->except('department_id'), [
            'entry_id' => auth('admin')->user()->id
        ]));


        $notify[] = ['success', "Deduction successfully Added"];
        return to_route('admin.salarydeduction.index')->withNotify($notify);
    }


    public function show(SalaryDeduction $salaryDeduction)
    {
        //
        Gate::authorize('admin.salarydeduction.show');
    }


    public function edit(SalaryDeduction $salarydeduction)
    {
        Gate::authorize('admin.salarydeduction.edit');
        $data['months'] = Month::get();
        $data['years'] = Year::latest()->get();
        $data['employees'] = Employee::get();
        return view('admin.hr.salarydeductions.edit', $data, compact('salarydeduction'));
    }


    public function update(Request $request, SalaryDeduction $salarydeduction)
    {
        Gate::authorize('admin.salarydeduction.update');
        $request->validate([
            'month_id'      => 'required',
            'employee_id'   => 'required',
            'amount'        => 'required'
        ]);

        $salarydeduction->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now()
        ]));


        $notify[] = ['success', "Deduction successfully Updated"];
        return to_route('admin.salarydeduction.index')->withNotify($notify);
    }


    public function destroy(SalaryDeduction $salarydeduction)
    {
        Gate::authorize('admin.salarydeduction.destroy');
        $salarydeduction->delete();
        $notify[] = ['success', "Deduction successfully Deleted"];
        return to_route('admin.salarydeduction.index')->withNotify($notify);
    }
}
