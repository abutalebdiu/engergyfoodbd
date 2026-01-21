<?php

namespace App\Http\Controllers\Admin\HR;

use PDF;
use App\Models\User;
use App\Models\HR\Loan;

use App\Models\HR\Employee;
use Illuminate\Http\Request;
use App\Models\HR\Attendance;
use App\Models\HR\Department;
use App\Models\HR\SalaryType;
use App\Models\HR\SalarySetup;
use App\Models\HR\SalaryAdvance;
use App\Models\HR\SalaryGenerate;
use App\Models\HR\SalaryDeduction;
use App\Models\HR\SalaryBonusSetup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\HR\SalaryPaymentHistory;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.employee.list');

 
        $employeeQuery = Employee::with('department');

        if ($request->department_id) {
            $data['department_id'] = $request->department_id;
            $employeeQuery->where('department_id', $request->department_id);
        }

        if ($request->status) {
            $employeeQuery->where('status', $request->status);
        } else {
            $employeeQuery->where('status', 'Active');
        }


        $departments = Department::whereHas('employees', function ($q) use ($employeeQuery) {
            $q->mergeConstraintsFrom($employeeQuery);
        })
            ->with(['employees' => function ($q) use ($employeeQuery) {
                $q->mergeConstraintsFrom($employeeQuery);
            }])
            ->paginate(2);

 
        $data['employeesByDepartment'] = $departments->getCollection()
            ->mapWithKeys(function ($department) {
                return [
                    $department->id => $department->employees
                ];
            });

        $data['salarySums'] = [];
        $data['foodallowanceSums'] = [];
        $data['empLoanAmountSums'] = [];
        $data['empLoanPaidAmountSums'] = [];
        $data['empLoanDueAmountSums'] = [];

        foreach ($data['employeesByDepartment'] as $deptId => $employees) {
            $data['salarySums'][$deptId] = $employees->sum(function ($e) {
                return $e->salary(); 
            });

            $data['foodallowanceSums'][$deptId] = $employees->sum(fn($e) => $e->food_allowance);
            $data['empLoanAmountSums'][$deptId] = $employees->sum(fn($e) => $e->loan_amount);
            $data['empLoanPaidAmountSums'][$deptId] = $employees->sum(fn($e) => $e->loan_paid);
            $data['empLoanDueAmountSums'][$deptId] = $employees->sum(fn($e) => $e->loan_due);
        }


        $data['departments'] = Department::get();
        $data['pagination'] = $departments;

     
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.hr.employees.employee_pdf', $data);
            return $pdf->stream('Employee_list.pdf');
        }

        return view('admin.hr.employees.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.employee.create');
        $data['departments'] = Department::get();
        return view('admin.hr.employees.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.employee.store');
        $request->validate([
            'name' => 'required',
        ]);

        $employee = Employee::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'food_allowance' => bn2en($request->food_allowance),
            'status' => 'Active'
        ]));
        if ($request->emp_id == null) {
            $employee->emp_id = "EMP00" . $employee->id;
        }

        $attachment = $request->attachment;
        if ($attachment) {
            $uniqname = uniqid();
            $ext = strtolower($attachment->getClientOriginalExtension());
            $filepath = 'uploads/employees/documents/';
            $imagename = $filepath . $uniqname . '.' . $ext;
            $attachment->move($filepath, $imagename);
            $employee->cv = $imagename;
        }
        $employee->save();


        $notify[] = ['success', 'Employee successfully Added'];
        return to_route('admin.employee.index')->withNotify($notify);
    }

    public function show(Employee $employee)
    {
        Gate::authorize('admin.employee.show');
        $data['salarytypes'] = SalaryType::get();
        return view('admin.hr.employees.show', compact('employee'), $data);
    }

    public function edit(Employee $employee)
    {
        Gate::authorize('admin.employee.edit');
        $data['departments'] = Department::get();
        return view('admin.hr.employees.edit', compact('employee'), $data);
    }

    public function update(Request $request, Employee $employee)
    {
        Gate::authorize('admin.employee.update');
        $request->validate([
            'name' => 'required',
        ]);

        $employee->update(array_merge($request->all(), [
            'edit_id'           => auth('admin')->user()->id,
            'food_allowance'    => bn2en($request->food_allowance),
            'edit_at'           => now(),
        ]));

        $attachment = $request->attachment;
        if ($attachment) {
            $uniqname = uniqid();
            $ext = strtolower($attachment->getClientOriginalExtension());
            $filepath = 'uploads/employees/documents/';
            $imagename = $filepath . $uniqname . '.' . $ext;
            $attachment->move($filepath, $imagename);
            $employee->attachment = $imagename;
        }
        $employee->save();

        $notify[] = ['success', 'Employee successfully Updated'];
        return to_route('admin.employee.index')->withNotify($notify);
    }

    public function destroy(Employee $employee)
    {

        Gate::authorize('admin.employee.destroy');

        Attendance::where('employee_id', $employee->id)->delete();
        Loan::where('employee_id', $employee->id)->delete();
        SalaryAdvance::where('employee_id', $employee->id)->delete();
        SalaryBonusSetup::where('employee_id', $employee->id)->delete();
        SalaryDeduction::where('employee_id', $employee->id)->delete();
        SalaryGenerate::where('employee_id', $employee->id)->delete();
        SalaryPaymentHistory::where('employee_id', $employee->id)->delete();
        SalarySetup::where('employee_id', $employee->id)->delete();
        User::where('reference_id', $employee->id)->update(['reference_id' => null]);

        $employee->delete();
        $notify[] = ['success', "Employee deleted successfully"];
        return back()->withNotify($notify);
    }


    public function status(Request $request, $id)
    {
        Gate::authorize('admin.employee.status');

        $employee = Employee::findOrFail($id);
        if ($employee->status == 'Active') {
            $employee->status = 'Inactive';
            $notify[] = ['success', 'Employee successfully Inactive'];
        } else {
            $employee->status = 'Active';
            $notify[] = ['success', 'Employee successfully Active'];
        }
        $employee->save();
        return back()->withNotify($notify);
    }


    public function paymenthistory($id)
    {
        $data['employee']           = Employee::find($id);
        $data['loans']              = Loan::where('employee_id',$id)->latest()->get();
        $data['salarygenerates']    = SalaryGenerate::where('employee_id',$id)->get();
        $data['salarypayments']     = SalaryPaymentHistory::where('employee_id',$id)->latest()->get();

        return view('admin.hr.employees.paymenthistory',$data);
    }


    public function paymenthistorypdf($id)
    {
        $data['employee']           = Employee::find($id);
        $data['loans']              = Loan::where('employee_id',$id)->latest()->get();
        $data['salarygenerates']    = SalaryGenerate::where('employee_id',$id)->get();
        $data['salarypayments']     = SalaryPaymentHistory::where('employee_id',$id)->latest()->get();

        $pdf = PDF::loadView('admin.hr.employees.paymenthistory_pdf', $data);
        return $pdf->stream('statement.pdf');
    }



}
