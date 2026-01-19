<?php

namespace App\Http\Controllers\Admin\Report;

 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Report\Liabilitie;

class LiabilitieController extends Controller
{

    public function index()
    {
        $data['liabilities'] = Liabilitie::active()->get();
        return view('report.liabilities.view', $data);
    }

    public function create()
    {
        return view('report.liabilities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'amount' => 'required',
        ]);

        Liabilitie::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Liabilitie successfully Added'];
        return to_route('admin.liabilitie.index')->withNotify($notify);
    }

    public function show(Liabilitie $liabilitie)
    {
         return view('admin.liabilities.show',compact('liabilitie'));
    }

    public function edit($id)
    {
        $liabilitie = Liabilitie::find($id);
        return view('admin.liabilities.edit',compact('liabilitie'));
    }

    public function update(Request $request, Liabilitie $liabilitie)
    {
        $request->validate([
            'name'      => 'required',
            'amount'    => 'required',
        ]);

        $liabilitie->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Liabilitie successfully Updated'];
        return to_route('admin.liabilitie.index')->withNotify($notify);
    }

    public function destroy(Liabilitie $liabilitie)
    {
        $liabilitie->delete();
        
        $notify[] = ['success', "Liabilitie deleted successfully"];
        return back()->withNotify($notify);
    }
}
