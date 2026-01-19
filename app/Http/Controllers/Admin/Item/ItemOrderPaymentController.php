<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\TransactionHistory;
use App\Models\Item;
use App\Models\ItemOrder;
use App\Models\ItemOrderPayment;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;
use Carbon\Carbon;

class ItemOrderPaymentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.itemorderpayment.list');

        $data['suppliers'] = User::active()->where('type', 'supplier')->get();

        $query = ItemOrderPayment::query();

        if ($request->supplier_id) {
            $data['supplier_id']        = $request->supplier_id;
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        else{
          
            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
             
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }

        $data['ordersupplierpayments'] =  $query->get();

        if ($request->has('search')) {
            return view('admin.items.item-order-payment.index', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.items.item-order-payment.item_order_payment_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } else {
            return view('admin.items.item-order-payment.index', $data);
        }
    }

    public function create()
    {
        Gate::authorize('admin.itemorderpayment.create');
        $data['suppliers'] = User::active()->where('type', 'supplier')->with('itemorderunpaids')->get();
        return view('admin.items.item-order-payment.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.itemorderpayment.store');
        $request->validate([
            'item_order_id'     => 'required',
            'amount'            => 'required',
            'supplier_id'       => 'required',
            'payment_method_id' => 'required',
            'account_id'        => 'required'
        ]);

        DB::beginTransaction();
        try {
            $ordersupplierpayment = ItemOrderPayment::create(array_merge($request->all(), [
                'entry_id' => auth('admin')->user()->id,
                'status' => 'Paid'
            ]));

            $ordersupplierpayment->tnx_no = 'IP000' . $ordersupplierpayment->id;
            $ordersupplierpayment->save();


            $item = ItemOrder::find($request->item_order_id);
            if ($request->amount >= $item->supplier_total_payable) {
                $item->paid_amount              = $item->paidamount($item->id);
                $item->supplier_total_payable   = $item->due_balance - $item->paidamount($item->id);
                $item->payment_status = "Paid";
                $item->save();
            } else {
                $item->paid_amount              = $item->paidamount($item->id);
                $item->supplier_total_payable   = $item->due_balance - $item->paidamount($item->id);
                $item->payment_status = "Partial";
                $item->save();
            }

            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no = $ordersupplierpayment->tnx_no;
            $transactionhistory->date = $request->date;
            $transactionhistory->reference_no = '';
            $transactionhistory->module_id = 3; // Supplier  Payment
            $transactionhistory->module_invoice_id = $ordersupplierpayment->id;
            $transactionhistory->amount = $request->amount;
            $transactionhistory->cdf_type = 'debit';
            $transactionhistory->payment_method_id = $request->payment_method_id;
            $transactionhistory->account_id = $request->account_id;
            $transactionhistory->client_id = $request->supplier_id;
            $transactionhistory->note = $request->note;
            $transactionhistory->save();

            $account = Account::find($request->account_id);

            // transaction hoar ager balance
            $transactionhistory->pre_balance = $account->main_balance;
            $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
            $transactionhistory->save();


            $account->main_balance = $account->main_balance - $request->amount;
            $account->save();

            // transaction hoar ager balance
            $transactionhistory->per_balance = $account->main_balance;
            $transactionhistory->save();


            $notify[] = ['success', 'Item Order Payment successfully Added'];

            DB::commit();
            return  back()->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function edit(ItemOrderPayment $itemorderpayment)
    {
       // Gate::authorize('admin.itemorderpayment.edit');
        return view('admin.items.item-order-payment.edit', compact('itemorderpayment'));
    }

    public function update(Request $request, ItemOrderPayment $itemorderpayment)
    {
      //  Gate::authorize('admin.itemorderpayment.update');
        $request->validate([
            'amount' => 'required',
        ]);

        $itemorderpayment->update(array_merge($request->all(), [
            'amount'  => bn2en($request->amount),
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Order Supplier Payment successfully Updated'];
        return to_route('admin.itemorderpayment.index')->withNotify($notify);
    }

    public function destroy(ItemOrderPayment $itemorderpayment)
    {
     //   Gate::authorize('admin.itemorderpayment.destroy');
        $itemorderpayment->delete();
        $notify[] = ['success', "Item Order Payment deleted successfully"];
        return back()->withNotify($notify);
    }
}
