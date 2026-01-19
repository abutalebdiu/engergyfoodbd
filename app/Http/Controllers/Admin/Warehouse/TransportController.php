<?php

namespace App\Http\Controllers\Admin\Warehouse;

use Illuminate\Http\Request;

use App\Models\Warehouse\Transport;
use App\Http\Controllers\Controller;


class TransportController extends Controller
{

    public function index()
    {
        $data['transports'] = Transport::active()->get();
        return view('admin.warehouses.transports.view', $data);
    }

    public function create()
    {
        return view('admin.warehouses.transports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Transport::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'Transport successfully Added'];
        return redirect('admin.Transport.index')->withNotify($notify);
    }

    public function show(Transport $transport)
    {
        return view('admin.warehouses.transports.show', compact('transport'));
    }

    public function edit(Transport $transport)
    {
        return view('admin.warehouses.transports.edit', compact('transport'));
    }

    public function update(Request $request, Transport $transport)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $transport->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Transport successfully Updated'];
        return to_route('admin.Transport.index')->withNotify($notify);
    }

    public function destroy(Transport $transport)
    {
        $transport->delete();
        $notify[] = ['success', "Transport deleted successfully"];
        return back()->withNotify($notify);
    }
}
