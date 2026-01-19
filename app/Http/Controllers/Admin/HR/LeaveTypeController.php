<?php

namespace App\Http\Controllers\Admin\HR;

use App\Models\HR\LeaveType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LeaveTypeController extends Controller
{

    public function index()
    {
        $data['leavetypes'] = LeaveType::active()->get();
        return view('admin.hr.leavetypes.view', $data);
    }

    public function create()
    {
        return view('admin.hr.leavetypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        LeaveType::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'Leave Type successfully Added'];
        return to_route('admin.leavetype.index')->withNotify($notify);
    }

    public function show(LeaveType $leaveType)
    {
        return view('admin.hr.leavetypes.show', compact('leaveType'));
    }

    public function edit(LeaveType $leavetype)
    {
        return view('admin.hr.leavetypes.edit', compact('leavetype'));
    }

    public function update(Request $request, LeaveType $leavetype)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $leavetype->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Leave Type successfully Updated'];
        return to_route('admin.leavetype.index')->withNotify($notify);
    }

    public function destroy(LeaveType $leavetype)
    {
        $leavetype->delete();
        $notify[] = ['success', "Leave Type deleted successfully"];
        return back()->withNotify($notify);
    }
}
