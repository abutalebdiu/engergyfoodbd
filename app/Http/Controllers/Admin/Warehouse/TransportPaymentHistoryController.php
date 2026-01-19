<?php

namespace App\Http\Controllers\Admin\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse\TransportPaymentHistory;


class TransportPaymentHistoryController extends Controller
{

    public function index()
    {
        $data['TransportPaymentHistorys'] = TransportPaymentHistory::active()->get();
        return view('admin.TransportPaymentHistory.view', $data);
    }

    public function create()
    {
        return view('admin.TransportPaymentHistory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        TransportPaymentHistory::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'TransportPaymentHistory successfully Added'];
        return redirect('admin.TransportPaymentHistory.index')->withNotify($notify);
    }

    public function show(TransportPaymentHistory $transportPaymentHistory)
    {
        return view('admin.TransportPaymentHistory.show', compact('transportPaymentHistory'));
    }

    public function edit(TransportPaymentHistory $transportPaymentHistory)
    {
        return view('admin.TransportPaymentHistory.edit', compact('transportPaymentHistory'));
    }

    public function update(Request $request, TransportPaymentHistory $transportPaymentHistory)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $transportPaymentHistory->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'TransportPaymentHistory successfully Updated'];
        return to_route('admin.TransportPaymentHistory.index')->withNotify($notify);
    }

    public function destroy(TransportPaymentHistory $transportPaymentHistory)
    {
        $transportPaymentHistory->delete();
        $notify[] = ['success', "TransportPaymentHistory deleted successfully"];
        return back()->withNotify($notify);
    }
}
