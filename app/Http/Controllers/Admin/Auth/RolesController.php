<?php

namespace App\Http\Controllers\Admin\Auth;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Setting\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
    public function index()
    {
        Gate::authorize('admin.role.list');
        $roles = Role::with('permission')->get();
        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        Gate::authorize('admin.role.create');
        $permissionGroups = Permission::all()->groupBy('group');

        return view('admin.role.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
      //    Gate::authorize('admin.role.store');
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'required|integer',
        ]);

        try {
            $role = new Role();
            $role->name = $request->name;
            $role->guard_name = $request->guard_name;
            $role->save();


            $role->permission()->sync($request->input('permissions'));
            
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Something went wrong!',
                'alert-type' => 'error'
            );

            return redirect()->route('admin.role.index')->with($notification);
        }

        $notification = array(
            'message' => 'New Role Add Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.role.index')->with($notification);
    }


    public function show($id)
    {
        //

        Gate::authorize('admin.role.show');
    }

    public function edit($id)
    {
        Gate::authorize('admin.role.edit');
        $role = Role::with('permission')->findOrFail($id);
        $selectedPermissions = $role->permission->pluck('pivot.permission_id')->toArray();
        $permissionGroups = Permission::all()->groupBy('group');


        return view('admin.role.edit', compact('role', 'selectedPermissions', 'permissionGroups'));
    }


    public function update(Request $request, $id)
    {
        Gate::authorize('admin.role.update');

        $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'required|integer',
        ]);


        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->guard_name = $request->guard_name;
            $role->save();


            $role->permission()->sync($request->input('permissions'));
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Something went wrong!',
                'alert-type' => 'error'
            );

            return redirect()->route('admin.role.index')->with($notification);
        }

        $notification = array(
            'message' => 'Role Update Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.role.index')->with($notification);
    }


    public function destroy($id)
    {
        Gate::authorize('admin.role.destroy');
        //
    }
}
