<?php

namespace App\Http\Controllers\Admin\HR;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HR\FestivalBonusPayment;
use App\Models\HR\FestivalBonusDetail;
use App\Models\HR\Employee;

class FestivalBonusPaymentController extends Controller
{

    public function index()
    {
        $data['festivalbonuspayments']  = FestivalBonusPayment::paginate(30)->withQueryString();
        $data['employees']              = Employee::get();
        return view('admin.hr.festivalbonuspayments.view',$data);
    }

    public function create()
    {
        return view('admin.FestivalBonusPayment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id'    => 'required',
            'amount'        => 'required',
        ]);

        FestivalBonusPayment::create(array_merge($request->all(), [
            'year'      => Date('Y'),
            'amount'    => bn2en($request->amount),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Paid'
        ]));
        
        FestivalBonusDetail::where('id',$request->festival_bonus_detail_id)->update(['status'=>'Paid']);

        $notify[] = ['success', 'Festival Bonus Successfully Paid'];
        return back()->withNotify($notify);
    }

    public function show(FestivalBonusPayment $festivalBonusPayment)
    {
         return view('admin.FestivalBonusPayment.show',compact('festivalBonusPayment'));
    }

    public function edit(FestivalBonusPayment $festivalBonusPayment)
    {
        return view('admin.FestivalBonusPayment.edit',compact('festivalBonusPayment'));
    }

    public function update(Request $request, FestivalBonusPayment $festivalBonusPayment)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $festivalBonusPayment->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'FestivalBonusPayment successfully Updated'];
        return to_route('admin.FestivalBonusPayment.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        
        $festivalBonusPayment = FestivalBonusPayment::find($id);
        
        FestivalBonusDetail::where('id',$festivalBonusPayment->festival_bonus_detail_id)->update(['status'=>'Active']);
        
        $festivalBonusPayment->delete();
        $notify[] = ['success', "FestivalBonusPayment deleted successfully"];
        return back()->withNotify($notify);
    }
}
