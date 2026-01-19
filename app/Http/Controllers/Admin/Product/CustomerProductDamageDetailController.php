<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Product\CustomerProductDamageDetail;


class CustomerProductDamageDetailController extends Controller
{

    public function index()
    {
        $data['CustomerProductDamageDetails'] = CustomerProductDamageDetail::active()->get();
        return view('admin.CustomerProductDamageDetail.view', $data);
    }

    public function create()
    {
        return view('admin.CustomerProductDamageDetail.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        CustomerProductDamageDetail::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'CustomerProductDamageDetail successfully Added'];
        return to_route('admin.CustomerProductDamageDetail.index')->withNotify($notify);
    }

    public function show(CustomerProductDamageDetail $customerProductDamageDetail)
    {
        return view('admin.CustomerProductDamageDetail.show', compact('customerProductDamageDetail'));
    }

    public function edit(CustomerProductDamageDetail $customerProductDamageDetail)
    {
        return view('admin.CustomerProductDamageDetail.edit', compact('customerProductDamageDetail'));
    }

    public function update(Request $request, CustomerProductDamageDetail $customerProductDamageDetail)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $customerProductDamageDetail->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'CustomerProductDamageDetail successfully Updated'];
        return to_route('admin.CustomerProductDamageDetail.index')->withNotify($notify);
    }

    public function destroy(CustomerProductDamageDetail $customerProductDamageDetail)
    {
        $customerProductDamageDetail->delete();
        $notify[] = ['success', "CustomerProductDamageDetail deleted successfully"];
        return back()->withNotify($notify);
    }
}
