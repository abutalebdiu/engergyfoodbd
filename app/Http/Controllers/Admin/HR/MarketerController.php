<?php

namespace App\Http\Controllers\Admin\HR;

use App\Models\HR\Marketer;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketerController extends Controller
{

    public function index()
    {
        $data['marketers'] = Marketer::get();
        return view('admin.hr.marketers.view',$data);
    }

    public function create()
    {
        return view('admin.hr.marketers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'amount'    => 'required',
        ]);

        Marketer::create(array_merge($request->all(), [
            'amount'    => bn2en($request->amount),
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Marketer successfully Added'];
        return to_route('admin.marketer.index')->withNotify($notify);
    }

    public function show(Marketer $marketer)
    {
        return view('admin.hr.marketers.show',compact('marketer'));
    }

    public function edit(Marketer $marketer)
    {
        return view('admin.hr.marketers.edit',compact('marketer'));
    }

    public function update(Request $request, Marketer $marketer)
    {
        $request->validate([
            'name'      => 'required',
            'amount'    => 'required',
        ]);

        $oldmarketercommission = $marketer->amount;

        $marketer->update(array_merge($request->all(), [
            'amount'   => bn2en($request->amount),
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        // if($oldmarketercommission != $request->amount)
        // {
            $getorder = Order::where('marketer_id',$marketer->id)->where('mc_invoice_id',null)->get();

            foreach($getorder as $order)
            {
                $order->marketer_commission = round(($order->net_amount * $marketer->amount)/100,2);
                $order->save();
            }

       // }

        $notify[] = ['success', 'Marketer successfully Updated'];
        return to_route('admin.marketer.index')->withNotify($notify);
    }

    public function destroy(Marketer $marketer)
    {
        $marketer->delete();
        $notify[] = ['success', "Marketer deleted successfully"];
        return back()->withNotify($notify);
    }


    public function status(Request $request, $id)
    {
        $marketer = Marketer::findOrFail($id);
        if ($marketer->status == 'Active') {
            $marketer->status = 'Inactive';
            $notify[] = ['success', 'Marketer successfully Inactive'];
        } else {
            $marketer->status = 'Active';
            $notify[] = ['success', 'Marketer successfully Active'];
        }
        $marketer->save();
        return back()->withNotify($notify);
    }
}
