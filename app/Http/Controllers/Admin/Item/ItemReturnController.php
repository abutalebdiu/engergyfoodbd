<?php

namespace App\Http\Controllers\Admin\Item;

use App\Models\User;
use App\Models\ItemOrder;
use App\Models\ItemReturn;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ItemOrderPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ItemOrderReturnDetail;
use App\Models\Account\OrderReturnPayment;
use App\Models\Account\TransactionHistory;

class ItemReturnController extends Controller
{
    public function index(Request $request)
    {

        Gate::authorize('admin.itemreturn.list');

        $data['customers'] = User::get(['id', 'name']);

        $query = ItemReturn::query();

        if ($request->customer_id) {
            $data['customer_id'] = $request->customer_id;
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data['orderreturns'] = $query->latest()->get();
        if ($request->has('search')) {
            return view('admin.items.itemreturns.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  Pdf::loadView('admin.items.itemreturns.orderreturn_pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('orderreturn_list.pdf');
        } elseif ($request->has('excel')) {
            return Excel::download(new OrderReturnExport($data), 'Orderreturn_list.xlsx');
        } else {
            return view('admin.items.itemreturns.view', $data);
        }
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.itemreturn.create');

        $data['order'] = ItemOrder::find($request->item_return_id);
        return view('admin.items.itemreturns.create', $data);
    }

    public function store(Request $request)
    {

        Gate::authorize('admin.itemreturn.store');
        $orderreturn = new ItemReturn();
        $orderreturn->order_id      = $request->order_id;
        $orderreturn->customer_id   = $request->customer_id;
        $orderreturn->totalamount   = $request->total_amount;

        $orderreturn->payment_status = 'Unpaid';
        $orderreturn->entry_id   = auth('admin')->user()->id;
        $orderreturn->save();


        $input = $request->all();

        foreach ($input['qty'] as $key => $val) {
            $returndetail = new ItemOrderReturnDetail();
            $returndetail->order_return_id = $orderreturn->id;
            $returndetail->order_detail_id = $input['order_detail_id'][$key];
            $returndetail->product_id = $input['product_id'][$key];
            $returndetail->purchase_price = $input['purchase_price'][$key];
            $returndetail->purchase_total = $input['purchase_price'][$key] * $input['qty'][$key];
            $returndetail->price = $input['price'][$key];
            $returndetail->qty = $input['qty'][$key];
            $returndetail->amount = $input['amount'][$key];
            $returndetail->entry_id = auth('admin')->user()->id;
            $returndetail->save();
        }

        $orderreturn->save();

        $notify[] = ['success', 'Item Order Return successfully Added'];
        return to_route('admin.itemreturn.index')->withNotify($notify);
    }

    public function show($id)
    {
        Gate::authorize('admin.itemreturn.show');

        $orderreturn = ItemReturn::find($id);

        return view('admin.items.itemreturns.show', compact('orderreturn'));
    }

    public function edit(ItemReturn $orderreturn)
    {
        Gate::authorize('admin.itemreturn.edit');
        return view('admin.items.itemreturns.edit', compact('orderreturn'));
    }

    public function update(Request $request, ItemReturn $orderreturn)
    {
        Gate::authorize('admin.itemreturn.update');
        $request->validate([
            'name' => 'required',
        ]);

        $orderreturn->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Item Order Return successfully Updated'];
        return to_route('admin.itemreturn.index')->withNotify($notify);
    }

    public function destroy(ItemReturn $orderreturn)
    {
        Gate::authorize('admin.itemreturn.destroy');
        $orderreturn->delete();
        $notify[] = ['success', "Item Order Return deleted successfully"];
        return back()->withNotify($notify);
    }
}
