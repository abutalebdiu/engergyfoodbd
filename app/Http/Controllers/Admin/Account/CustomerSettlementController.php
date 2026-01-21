<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\CustomerSettlement;
use Illuminate\Http\Request;

class CustomerSettlementController extends Controller
{
    public function index(Request $request)
    {
        $data['customer_settlements'] = CustomerSettlement::latest()->get();
        return view('admin.accounts.customer_settlements.view', $data);
    }

    public function create()
    {
        return view('admin.accounts.customer_settlements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'type' => 'required',
        ]);

        CustomerSettlement::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Customer settlement successfully Added'];
        return to_route('admin.customersettlement.index')->withNotify($notify);
    }

    public function show(CustomerSettlement $customer_settlement)
    {
        return view('admin.accounts.customer_settlements.show', compact('customer_settlement'));
    }

    public function edit($id)
    {
        $customer_settlement = CustomerSettlement::findOrFail($id);
        return view('admin.accounts.customer_settlements.edit', compact('customer_settlement'));
    }

    public function update(Request $request, CustomerSettlement $customer_settlement)
    {
        $request->validate([
            'customer_id' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'type' => 'required',
        ]);

        $customer_settlement->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Customer settlement successfully Updated'];
        return to_route('admin.customersettlement.index')->withNotify($notify);
    }


    public function destroy(CustomerSettlement $customer_settlement)
    {
        $customer_settlement->delete();
        $notify[] = ['success', "Customer settlement deleted successfully"];
        return back()->withNotify($notify);
    }
}
