<?php

namespace App\Http\Controllers\Admin\HR;


use Carbon\Carbon;
use App\Models\HR\Employee;
use Illuminate\Http\Request;
use App\Models\HR\FestivalBonus;
use App\Http\Controllers\Controller;
use App\Models\HR\FestivalBonusDetail;

class FestivalBonusController extends Controller
{

    public function index()
    {
        $data['festivalbonuses'] = FestivalBonus::latest()->get();
        return view('admin.hr.festivalbonuses.index', $data);
    }

    public function create()
    {
        return view('admin.hr.festivalbonuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        FestivalBonus::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id
        ]));

        $notify[] = ['success', 'FestivalBonus successfully Added'];
        return to_route('admin.festivalbonus.index')->withNotify($notify);
    }

    public function show($id)
    {
        $festivalbonus = FestivalBonus::find($id);

        // $data['employeesByDepartment'] = Employee::with('department')
        //     ->where('bonus_eligibility', 'Yes')
        //     ->where('status', 'Active')
        //     ->get()
        //     ->groupBy('department_id');


        $employees =  Employee::where('bonus_eligibility', 'Yes')
            ->where('status', 'Active')
            ->get();

        $bonusamount = 0;

        foreach ($employees as $employee) {
            $festivalBonusDate = Carbon::parse($festivalbonus->date);
            $joinDate = Carbon::parse($employee->joindate);
            $diff = $joinDate->diff($festivalBonusDate);

            $serviceLength = "{$diff->y} Years, {$diff->m} Months, {$diff->d} Days";
            $basicAmount = $employee->salary / 2;
            $bonusamount = 0;

            if ($diff->y > 0) {
                $bonusamount = $basicAmount;
            } else {
                $bonusamount = round(($basicAmount / 12) * $diff->m);
            }


            $detail = new FestivalBonusDetail();
            $detail->festival_bonus_id  = $id;
            $detail->employee_id        = $employee->id;
            $detail->join_date          = $employee->joindate;
            $detail->bonus_percentage   = $festivalbonus->percentage;
            $detail->salary_amount      = $employee->salary;
            $detail->basic_amount       = $basicAmount;
            $detail->amount             = $bonusamount;
            $detail->entry_id           = auth('admin')->user()->id;
            $detail->status             = 'Active';
            $detail->save();
        }


        $notify[] = ['success', 'Festival Bonus successfully Generated'];
        return to_route('admin.festivalbonusdetail.index')->withNotify($notify);

        // return view('admin.hr.festivalbonuses.show', compact('festivalbonus'), $data);
    }

    public function edit($id)
    {
        $festivalbonus = FestivalBonus::find($id);
        return view('admin.hr.festivalbonuses.edit', compact('festivalbonus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $festivalbonus = FestivalBonus::find($id);
        $festivalbonus->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Festival Bonus successfully Updated'];
        return to_route('admin.festivalbonus.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $festivalbonus = FestivalBonus::find($id);
        FestivalBonusDetail::where('festival_bonus_id', $id)->delete();
        $festivalbonus->delete();


        $notify[] = ['success', "Festival Bonus deleted successfully"];
        return back()->withNotify($notify);
    }
}
