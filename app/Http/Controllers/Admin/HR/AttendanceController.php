<?php

namespace App\Http\Controllers\Admin\HR;

use Illuminate\Http\Request;
use App\Models\HR\Attendance;
use App\Http\Controllers\Controller;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\Setting\Month;
use App\Models\Setting\Year;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.attendance.list');

        $data['departments']    = Department::get();
        $data['months']         = Month::get();
        $data['years']          = Year::get();


        $query = Attendance::with(['employee.department', 'entryuser', 'month', 'year'])
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->select('attendances.*', 'employees.id as employee_id');

        if ($request->department_id) {
            $data['department_id']      = $request->department_id;
            $query->where('employees.department_id', $request->department_id);
        }

        if ($request->month_id) {
            $data['month_id'] = $request->month_id;
            $query->where('month_id', $request->month_id);
        }
        
        else{
             $query->where('month_id', Date('m'));
        }
        
        if ($request->year_id) {
            $data['year_id'] = $request->year_id;
            $query->where('year_id', $request->year_id);
        }

        $attendances = $query
            ->orderBy('year_id', 'desc')
            ->orderBy('month_id', 'desc')
            ->get()
            ->groupBy(function ($attendance) {
                return   $attendance->month->name . '-' . $attendance->year->name;
            });


        $data['attendancesByYearMonth'] = $attendances;
        return view('admin.hr.attendances.view', $data);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.attendance.create');
        $data['departments']    = Department::get();
        $data['months']         = Month::get();
        $data['years']          = Year::get();

        $query = Employee::query();
        if ($request->department_id) {
            $data['searching']          = 'Yes';
            $data['department_id']      = $request->department_id;
            $query->where('department_id', $request->department_id);
        } else {
            $data['searching']          = 'No';
        }

        if ($request->month_id) {
            $data['month_id'] = $request->month_id;
        }
        if ($request->year_id) {
            $data['year_id'] = $request->year_id;
        }

        $data['employees'] = $query->where('status', 'Active')->get();

        return view('admin.hr.attendances.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.attendance.store');
        $request->validate([
            'month_id'  => 'required',
            'year_id'   => 'required',
        ]);

        foreach ($request->employee_id as $key =>  $employee_id) {
            Attendance::where('employee_id', $employee_id)->where('month_id', $request->month_id)->where('year_id', $request->year_id)->delete();
            $attendance =  new Attendance();
            $attendance->employee_id    = $employee_id;
            $attendance->month_id       = $request->month_id;
            $attendance->year_id        = $request->year_id;
            $attendance->days           = $request->days[$key];
            $attendance->entry_id       = auth('admin')->user()->id;
            $attendance->status         = 'Present';
            $attendance->save();
        }

        $notify[] = ['success', 'Attendance successfully Added'];
        return to_route('admin.attendance.index')->withNotify($notify);
    }

    public function show(Attendance $attendance)
    {
        Gate::authorize('admin.attendance.show');
        return view('admin.humanresources.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        Gate::authorize('admin.attendance.edit');
        $data['employees'] = Employee::active()->get();
        $data['months'] = Month::get();
        $data['years'] = Year::get();
        return view('admin.hr.attendances.edit', compact('attendance'), $data);
    }

    public function update(Request $request, Attendance $attendance)
    {
        Gate::authorize('admin.attendance.update');
        $request->validate([
            'employee_id' => 'required',
        ]);

        $attendance->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Attendance successfully Updated'];
        return to_route('admin.attendance.index')->withNotify($notify);
    }

    public function destroy(Attendance $attendance)
    {
        Gate::authorize('admin.attendance.destroy');
        $attendance->delete();
        $notify[] = ['success', "Attendance deleted successfully"];
        return back()->withNotify($notify);
    }
}
