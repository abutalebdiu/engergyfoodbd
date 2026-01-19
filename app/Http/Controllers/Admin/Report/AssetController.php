<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssetController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.asset.list');

        $data['assets'] = Asset::active()->get();
        return view('report.assets.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.asset.create');
        return view('report.assets.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.asset.store');
        $request->validate([
            'title' => 'required',
        ]);

        Asset::create(array_merge($request->all(), [
            'price'     => bn2en($request->price),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Asset successfully Added'];
        return to_route('admin.asset.index')->withNotify($notify);
    }

    public function show(Asset $asset)
    {
        Gate::authorize('admin.asset.show');
        return view('report.assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        Gate::authorize('admin.asset.edit');
        return view('report.assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        Gate::authorize('admin.asset.update');
        $request->validate([
            'title' => 'required',
        ]);

        $asset->update(array_merge($request->all(), [
            'price'     => bn2en($request->price),
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Asset successfully Updated'];
        return to_route('admin.asset.index')->withNotify($notify);
    }

    public function destroy(Asset $asset)
    {
        Gate::authorize('admin.asset.destroy');
        $asset->delete();
        $notify[] = ['success', "Asset deleted successfully"];
        return back()->withNotify($notify);
    }
}
