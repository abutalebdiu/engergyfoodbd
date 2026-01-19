<?php

namespace App\Http\Controllers\Admin\HR;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HR\FestivalBonusDetail;
use PDF;
use App\Models\HR\FestivalBonusPayment;

class FestivalBonusDetailController extends Controller
{

    public function index(Request $request)
    {
        $data['festivalbonusdetailgroupes'] = FestivalBonusDetail::with('employee')->where('festival_bonus_id',3)->get()->groupBy('employee.department_id');


        if ($request->has('search')) {
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.hr.festivalbonusdetails.index_pdf', $data);
            return $pdf->stream('employee_bonus_list.pdf');
        } else {
            return view('admin.hr.festivalbonusdetails.index', $data);
        }
    }

    public function create()
    {
        return view('admin.FestivalBonusDetail.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        FestivalBonusDetail::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'FestivalBonusDetail successfully Added'];
        return to_route('admin.FestivalBonusDetail.index')->withNotify($notify);
    }

    public function show($id)
    {
        $data['festivalbonusdetail']    = FestivalBonusDetail::find($id);
        $data['payments']               = FestivalBonusPayment::where('festival_bonus_detail_id',$id)->get();
        return view('admin.hr.festivalbonusdetails.show', $data);
    }

    public function edit($id)
    {
        $data['festivalbonusdetail'] = FestivalBonusDetail::find($id);
        return view('admin.hr.festivalbonusdetails.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required',
        ]);

        $festivalBonusDetail = FestivalBonusDetail::find($id);

        $festivalBonusDetail->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Festival Bonus successfully Updated'];
        return to_route('admin.festivalbonusdetail.index')->withNotify($notify);
    }

    public function destroy(FestivalBonusDetail $festivalBonusDetail)
    {
        $festivalBonusDetail->delete();
        $notify[] = ['success', "FestivalBonusDetail deleted successfully"];
        return back()->withNotify($notify);
    }
}
