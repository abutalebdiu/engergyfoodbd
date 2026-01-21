<?php

namespace App\Http\Controllers\Admin\HR;

use Carbon\Carbon;
use App\Models\HR\Employee;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Setting\Month;
use App\Models\HR\SalaryBonusSetup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use PDF;

class SalaryBonusSetupController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.salarybonussetup.list');

        $data['departments'] = Department::orderBy('position', 'ASC')->get();

        $data['employees'] = Employee::where('status', 'Active')->get();

        $query = SalaryBonusSetup::query();



        if ($request->employee_id) {
            $data['employee'] = $request->employee_id;
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->isCurrentMonth());
        }

        $data['bonuses'] = $query->latest()->paginate(100);

        if ($request->has('search')) {
            return view('admin.hr.bonus.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.hr.bonus.view_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {
        } else {
            return view('admin.hr.bonus.view', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.salarybonussetup.create');

        $data['months'] = Month::get();
        $data['departments'] = Department::orderBy('position', 'ASC')->get();
        $data['years'] = Year::orderBy('name', 'DESC')->get();
        return view('admin.hr.bonus..create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.salarybonussetup.store');

        $request->validate([
            'employee_id'   => 'required',
            'month_id'      => 'required',
            'year_id'       => 'required'
        ]);

        SalaryBonusSetup::create(array_merge($request->except('department_id'), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', "Bonus successfully Added"];
        return to_route('admin.salarybonussetup.index')->withNotify($notify);
    }

    public function show(SalaryBonusSetup $salarybonussetup)
    {
        Gate::authorize('admin.salarybonussetup.show');
        return view('admin.hr.bonus..show', compact('salarybonussetup'));
    }

    public function edit(SalaryBonusSetup $salarybonussetup)
    {
        Gate::authorize('admin.salarybonussetup.edit');
        $data['months'] = Month::get();
        $data['employees'] = Employee::get();
        $data['years'] = Year::orderBy('name', 'DESC')->get();
        return view('admin.hr.bonus..edit', compact('salarybonussetup'), $data);
    }

    public function update(Request $request, SalaryBonusSetup $salarybonussetup)
    {
        Gate::authorize('admin.salarybonussetup.update');
        $request->validate([
            'employee_id'   => 'required',
            'month_id'      => 'required',
            'year_id'       => 'required'
        ]);

        $salarybonussetup->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', "Bonus successfully updated"];
        return to_route('admin.salarybonussetup.index')->withNotify($notify);
    }

    public function destroy(SalaryBonusSetup $salarybonussetup)
    {
        Gate::authorize('admin.salarybonussetup.destroy');

        $salarybonussetup->delete();
        $notify[] = ['success', "Bonus successfully Deleted"];
        return to_route('admin.salarybonussetup.index')->withNotify($notify);
    }
}
