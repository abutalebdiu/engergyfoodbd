<?php

namespace App\Http\Controllers\Admin\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HR\AttendanceApplication;


class AttendanceApplicationController extends Controller
{

    public function index()
    {
        $data['AttendanceApplications'] = AttendanceApplication::active()->get();

        return view('admin.AttendanceApplication.view', $data);
    }

    public function create()
    {
        return view('admin.AttendanceApplication.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        AttendanceApplication::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'AttendanceApplication successfully Added'];
        return redirect('admin.AttendanceApplication.index')->withNotify($notify);
    }

    public function show(AttendanceApplication $attendanceApplication)
    {
        return view('admin.AttendanceApplication.show', compact('attendanceApplication'));
    }

    public function edit(AttendanceApplication $attendanceApplication)
    {
        return view('admin.AttendanceApplication.edit', compact('attendanceApplication'));
    }

    public function update(Request $request, AttendanceApplication $attendanceApplication)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $attendanceApplication->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'AttendanceApplication successfully Updated'];
        return to_route('admin.AttendanceApplication.index')->withNotify($notify);
    }

    public function destroy(AttendanceApplication $attendanceApplication)
    {
        $attendanceApplication->delete();
        $notify[] = ['success', "AttendanceApplication deleted successfully"];
        return back()->withNotify($notify);
    }
}
