<?php

namespace App\Http\Controllers\Admin\HR;


use PDF;
use App\Models\HR\Employee;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Setting\Month;
use App\Models\HR\SalaryGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SalaryGenerateController extends Controller
{

    public function index(Request $request)
    {

        Gate::authorize('admin.salarygenerate.list');

        $data['months'] = Month::get();
        $data['years'] = Year::get();


        $data['salarygenerates'] = SalaryGenerate::select('month_id', 'year_id', DB::raw('SUM(payable_amount) as total_amount'))
                                    ->groupBy('month_id', 'year_id')
                                    ->orderBy('month_id', 'desc')
                                    ->orderBy('year_id', 'desc')
                                    ->get();

        // if ($request->has('search')) {
        //     return view('admin.hr.salarygenerates.view', $data);
        // } elseif ($request->has('pdf')) {
        //     $pdf = PDF::loadView('admin.hr.salarygenerates.salary_generate_pdf', $data, [], [
        //         'format' => 'a4',
        //         'orientation' => 'landscape'
        //     ]);
        //     return $pdf->stream('salary_list.pdf');
        // } else {

        //}
         
        
        
        //  $getsalarygenerates = SalaryGenerate::where('year_id',4)->where('month_id',8)->get();
         
        //  foreach($getsalarygenerates as $sal)
        //  {
        //      $employee = Employee::find($sal->employee_id);
        //      $sal->due_loan = $employee->receiableloan($sal->employee_id);
        //      $sal->save();
        //  } 
        
        return view('admin.hr.salarygenerates.view', $data);

    }


    public function getDepartmentWiseSalaries(Request $request)
    {
        Gate::authorize('admin.salarygenerate.departmentwise.list');

        $data['departments'] = Department::get();
        $data['months'] = Month::get();
        $data['years'] = Year::get();

        // Query the salary data with the related department
        $query = SalaryGenerate::with(['employee.department', 'entryuser', 'month', 'year'])
            ->join('employees', 'employees.id', '=', 'salary_generates.employee_id');

        // Apply filters (month, year, department)
        if ($request->department_id) {
            $query->where('employees.department_id', $request->department_id);
        }

        if ($request->month_id) {
            $query->where('month_id', $request->month_id);
        }

        if ($request->year_id) {
            $query->where('year_id', $request->year_id);
        }

        // Group salaries by department
        $salaries = $query->orderBy('year_id', 'desc')
            ->orderBy('month_id', 'desc')
            ->get()
            ->groupBy(function ($salary) {
                return optional($salary->employee->department)->name;
            });

        // Initialize totals array
        $departmentTotals = [];

        // Calculate department totals
        foreach ($salaries as $department => $salaryGroup) {
            $totals = [
                'total_salary' => $salaryGroup->sum('salary_amount'),
                'total_loan' => $salaryGroup->sum('loan_amount'),
                'total_advance' => $salaryGroup->sum('advance_salary_amount'),
                'total_bonus' => $salaryGroup->sum('bonus_amount'),
                'total_deduction' => $salaryGroup->sum('fine_amount'),
                'total_payable' => $salaryGroup->sum('payable_amount'),
                // Add other necessary sums here...
            ];

            $departmentTotals[$department] = $totals;
        }

        return view('admin.hr.salarygenerates.salarygenerates_department_wise', compact('salaries', 'departmentTotals'), $data);
    }


    public function create()
    {
        Gate::authorize('admin.salarygenerate.create');
        $data['departments'] = Department::orderBy('position', 'ASC')->get();
        $data['months']  = Month::get();
        $data['years']  = Year::latest()->get();
        
        return view('admin.hr.salarygenerates.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.salarygenerate.store');

        $request->validate([
            'month_id' => 'required',
            'year_id' => 'required',
        ]);

        $query = Employee::query();

        if ($request->employee_id) {
            $query->where('id', $request->employee_id);
        }

        $employees = $query->where('status', 'Active')->get();

        foreach ($employees  as $employee) {
             SalaryGenerate::where('employee_id', $employee->id)
                    ->where('month_id', $request->month_id)
                    ->where('year_id', $request->year_id)
                    ->delete();


            // Salary Generate
            $salarygenerate = new SalaryGenerate();
            $salarygenerate->employee_id            = $employee->id;
            $salarygenerate->month_id               = $request->month_id;
            $salarygenerate->year_id                = $request->year_id;
            $salarygenerate->salary                 = $employee->salary;
            $salarygenerate->per_day_salary         = $employee->daily_salary;
            $salarygenerate->food_allowance         = $employee->food_allowance;
            $salarygenerate->total_food_allowance   = $employee->foodallowance($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->total_present          = $employee->totalpresent($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->salary_amount          = $employee->salarysetup($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->advance_salary_amount  = $employee->advancesalary($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->bonus_amount           = $employee->bonus($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->loan_amount            = $employee->loan($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->fine_amount            = $employee->salarydeduction($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->payable_amount         = $employee->payableamount($employee->id, $request->month_id, $request->year_id);
            $salarygenerate->entry_id               =  auth('admin')->user()->id;
            $salarygenerate->status                 = 'Generated';
            $salarygenerate->save();

            // Loan Update
            if ($employee->loan($employee->id, $request->month_id, $request->year_id) > 0) {
                $employee = Employee::find($employee->id);
                $employee->loan_paid    = $employee->paidloan($employee->id);
                $employee->loan_due     = $employee->receiableloan($employee->id);
                $employee->save();


                $salarygenerate->due_loan = $employee->receiableloan($employee->id);
                $salarygenerate->save();
            }
        }

        $notify[] = ['success', "Salary Generate successfully Added"];
        return to_route('admin.salarygenerate.index')->withNotify($notify);
    }

    public function show(Request $request, SalaryGenerate $salarygenerate)
    {
      $data['months']  = Month::get();
      $data['years']  = Year::latest()->get();
      $data['departments'] = Department::get();
      
      $query = SalaryGenerate::with(['employee.department', 'entryuser', 'month', 'year'])
                    ->join('employees', 'employees.id', '=', 'salary_generates.employee_id')
                    ->select('salary_generates.*', 'employees.id as employee_id');


            if ($request->department_id) {
                $data['department_id'] = $request->department_id;
                $query->where('employees.department_id', $request->department_id);
            }

            if ($request->month_id) {
                $data['month_id'] = $request->month_id;
                $query->where('month_id', $request->month_id);
            }


            if ($request->year_id) {
                $data['year_id'] = $request->year_id;
                $query->where('year_id', $request->year_id);
            }


            $salarygenerates = $query
                                    ->orderBy('year_id', 'desc')
                                    ->orderBy('month_id', 'desc')
                                    ->get()
                                    ->groupBy(function ($salary) {
                                        return optional($salary->employee->department)->name;
                                    });


            $data['salarygeneratesByDepartment'] = $salarygenerates;



        if ($request->ajax()) {
            return view('admin.hr.salarygenerates.salary_generate_table', $data)->render();
        }
    

        if ($request->has('search')) {
            return view('admin.hr.salarygenerates.show', compact('salarygenerate'),$data);
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.hr.salarygenerates.salary_generate_pdf', $data, [], [
                'format' => 'a4',
                'orientation' => 'landscape'
            ]);
            return $pdf->stream('salary_list.pdf');
        } else {
            return view('admin.hr.salarygenerates.show', compact('salarygenerate'),$data);
        }

    }

    public function edit(SalaryGenerate $salarygenerate)
    {
      //  Gate::authorize('admin.salarygenerate.edit');
        $data['months']  = Month::get();
        $data['years']  = Year::latest()->get();
        $data['employees'] = Employee::get();
        return view('admin.hr.salarygenerates.edit', compact('salarygenerate'), $data);
    }

    public function update(Request $request, SalaryGenerate $salarygenerate)
    {
        Gate::authorize('admin.salarygenerate.update');
        $request->validate([
            'employee_id'   => 'required',
            'month_id'      => 'required',
            'year_id'       => 'required',
        ]);

        $salarygenerate->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', "Salary Generate successfully Updated"];
        return to_route('admin.salarygenerate.index')->withNotify($notify);
    }

    public function destroy(SalaryGenerate $salarygenerate)
    {
        Gate::authorize('admin.salarygenerate.destroy');

        $salarygenerate->delete();
        $notify[] = ['success', "Salary successfully deleted"];
        return back()->withNotify($notify);
    }
}
