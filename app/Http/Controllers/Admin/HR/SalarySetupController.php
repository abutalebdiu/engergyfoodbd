<?php

namespace App\Http\Controllers\Admin\HR;

use App\Models\HR\Employee;
use Illuminate\Http\Request;
use App\Models\HR\SalaryType;
use App\Models\HR\SalarySetup;
use App\Http\Controllers\Controller;

class SalarySetupController extends Controller
{

    public function index()
    {
        $data['salarysetupes'] = SalarySetup::active()->get();
        return view('admin.humanresources.salarysetupes.view', $data);
    }


    public function create()
    {
        $data['employees'] = Employee::active()->get();

        $data['salaryTypes'] = SalaryType::active()->get();

        return view('admin.humanresources.salarysetupes.create', $data);
    }



    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required'
        ]);

        $input = $request->all();

        foreach ($input['salary_type_id'] as $key => $value) {
            $countdata = SalarySetup::where('employee_id', $request->employee_id)->where('salary_type_id', $input['salary_type_id'][$key])->count();
            if ($countdata > 0) {
                $salarysetup =  SalarySetup::where('salary_type_id', $input['salary_type_id'][$key])->where('employee_id', $request->employee_id)->first();
                $salarysetup->employee_id = $request->employee_id;
                $salarysetup->salary_type_id = $input['salary_type_id'][$key];
                $salarysetup->amount = bn2en($input['amount'][$key]);
                $salarysetup->save();
            } else {
                $salarysetup = new SalarySetup();
                $salarysetup->employee_id       = $request->employee_id;
                $salarysetup->salary_type_id    = $input['salary_type_id'][$key];
                $salarysetup->amount            = bn2en($input['amount'][$key]);
                $salarysetup->save();
            }

            $employee = Employee::find($request->employee_id);
            $employee->salary       = $employee->salarysum($employee->id);
            $employee->daily_salary = round($employee->salarysum($employee->id) / 30, 2);
            $employee->save();
        }

        $notify[] = ['success', 'Salary Setup successfully Added'];
        return back()->withNotify($notify);
    }

    public function show(SalarySetup $salarySetup)
    {
        return view('admin.humanresources.salarysetupes.show', compact('salarySetup'));
    }

    public function edit(SalarySetup $salarySetup)
    {
        return view('admin.humanresources.salarysetupes.edit', compact('salarySetup'));
    }

    public function update(Request $request, SalarySetup $salarySetup)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $salarySetup->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Salary Setup successfully Updated'];
        return to_route('admin.salarysetup.index')->withNotify($notify);
    }

    public function destroy(SalarySetup $salarySetup)
    {
        $salarySetup->delete();
        $notify[] = ['success', "Salary Setup deleted successfully"];
        return back()->withNotify($notify);
    }
}
