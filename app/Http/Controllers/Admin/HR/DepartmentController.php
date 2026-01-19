<?php

namespace App\Http\Controllers\Admin\HR;

use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.department.list');

        $data['departments'] = Department::get();
        return view('admin.hr.departments.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.department.create');
        return view('admin.hr.departments.create');
    }


    public function store(Request $request)
    {
        Gate::authorize('admin.department.store');

        $request->validate([
            'name' => 'required'
        ]);

        Department::create(array_merge($request->all(), [
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Department successfully Added'];
        return to_route('admin.department.index')->withNotify($notify);
    }


    public function show(string $id)
    {
        Gate::authorize('admin.department.show');
        //
    }


    public function edit(Department $department)
    {
        Gate::authorize('admin.department.edit');
        return view('admin.hr.departments.edit', compact('department'));
    }


    public function update(Request $request, Department $department)
    {
        Gate::authorize('admin.department.update');
        $request->validate([
            'name' => 'required',
        ]);

        $department->update(array_merge($request->all(), [
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'Department successfully Updated'];
        return to_route('admin.department.index')->withNotify($notify);
    }


    public function destroy(Department $department)
    {
        Gate::authorize('admin.department.destroy');
        $department->delete();
        $notify[] = ['success', "Department Successfully Deleted"];
        return back()->withNotify($notify);
    }
}
