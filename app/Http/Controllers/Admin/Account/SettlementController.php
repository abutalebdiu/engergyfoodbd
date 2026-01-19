<?php

namespace App\Http\Controllers\Admin\Account;
use App\Http\Controllers\Controller;
use App\Models\Account\Settlement;
use Illuminate\Http\Request;


class SettlementController extends Controller
{

    public function index(Request $request)
    {
        $data['settlements'] = Settlement::latest()->get();
        return view('admin.accounts.settlements.view',$data);
    }

    public function create()
    {
        return view('admin.accounts.settlements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Settlement::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Settlement successfully Added'];
        return to_route('admin.Settlement.index')->withNotify($notify);
    }

    public function show(Settlement $settlement)
    {
         return view('admin.accounts.settlements.show',compact('settlement'));
    }

    public function edit(Settlement $settlement)
    {
        return view('admin.accounts.settlements.edit',compact('settlement'));
    }

    public function update(Request $request, Settlement $settlement)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $settlement->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Settlement successfully Updated'];
        return to_route('admin.settlement.index')->withNotify($notify);
    }

    public function destroy(Settlement $settlement)
    {
        $settlement->delete();
        $notify[] = ['success', "Settlement deleted successfully"];
        return back()->withNotify($notify);
    }
}
