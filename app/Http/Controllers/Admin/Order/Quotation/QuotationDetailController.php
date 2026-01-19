<?php

namespace App\Http\Controllers\Admin\Order\Quotation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Order\QuotationDetail;

class QuotationDetailController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.quotationdetail.list');

        $data['QuotationDetails'] = QuotationDetail::active()->get();

        return view('admin.QuotationDetail.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.quotationdetail.create');
        return view('admin.QuotationDetail.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.quotationdetail.store');
        $request->validate([
            'name' => 'required',
        ]);

        QuotationDetail::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'QuotationDetail successfully Added'];
        return to_route('admin.QuotationDetail.index')->withNotify($notify);
    }

    public function show(QuotationDetail $quotationDetail)
    {
        Gate::authorize('admin.quotationdetail.show');
        return view('admin.QuotationDetail.show', compact('quotationDetail'));
    }

    public function edit(QuotationDetail $quotationDetail)
    {
        Gate::authorize('admin.quotationdetail.edit');
        return view('admin.QuotationDetail.edit', compact('quotationDetail'));
    }

    public function update(Request $request, QuotationDetail $quotationDetail)
    {
        Gate::authorize('admin.quotationdetail.update');
        $request->validate([
            'name' => 'required',
        ]);

        $quotationDetail->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'QuotationDetail successfully Updated'];
        return to_route('admin.QuotationDetail.index')->withNotify($notify);
    }

    public function destroy(QuotationDetail $quotationdetail)
    {
        $quotationdetail->delete();
        $notify[] = ['success', "Quotation Detail deleted successfully"];
        return back()->withNotify($notify);
    }
}
