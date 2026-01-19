<?php


namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Models\Account\ModuleType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;


class ModuleTypeController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.moduletype.list');

        $data['moduletypes'] = ModuleType::active()->get();
        return view('admin.accounts.moduletypes.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.moduletype.create');

        return view('admin.accounts.moduletypes.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.moduletype.store');

        $request->validate([
            'name' => 'required',
            'short_code' => 'required',
        ]);

        ModuleType::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'Module Type successfully Added'];
        return to_route('admin.moduletype.index')->withNotify($notify);
    }

    public function show(ModuleType $moduleType)
    {
        Gate::authorize('admin.moduletype.show');

        return view('admin.ModuleType.show', compact('moduleType'));
    }

    public function edit(ModuleType $moduletype)
    {
        Gate::authorize('admin.moduletype.edit');

        return view('admin.accounts.moduletypes.edit', compact('moduletype'));
    }

    public function update(Request $request, ModuleType $moduletype)
    {
        Gate::authorize('admin.moduletype.update');

        $request->validate([
            'name' => 'required',
            'short_code' => 'required',
        ]);

        $moduletype->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'ModuleType successfully Updated'];
        return to_route('admin.moduletype.index')->withNotify($notify);
    }

    public function destroy(ModuleType $moduletype)
    {
        Gate::authorize('admin.moduletype.destroy');

        $moduleType->delete();
        $notify[] = ['success', "Module Type deleted successfully"];
        return back()->withNotify($notify);
    }
}
