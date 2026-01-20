<?php

namespace App\Http\Controllers\Admin\Item;

use PDF;
use App\Models\Item;
use App\Models\User;
use App\Models\ItemOrder;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Models\ItemOrderDetail;
use App\Models\ItemOrderPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class ItemOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('admin.itemorder.list');
        $data['suppliers'] = User::active()->where('type', 'supplier')->get();


        $query = ItemOrder::query();

        if ($request->supplier_id) {
            $data['supplier_id']        = $request->supplier_id;
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(100));
        }

        if($request->ajax()){

            $data['items'] = $query->latest()->paginate(20);

            return response()->json([
                "status" => true,
                "message" => "Data show successfully",
                "html" => view('admin.items.item-orders.inc.__item_order_table', $data)->render(),
            ], 200);
        }


        $data['items'] = $query->latest()->get();

        if ($request->has('search')) {
            return view('admin.items.item-orders.index', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.items.item-orders.item_order_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } else {
            return view('admin.items.item-orders.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('admin.itemorder.create');

        $data['itemcategories'] = ItemCategory::get();
        $data['items'] = Item::orderby('name', 'asc')->get();

        // return $data;

        $data['suppliers'] = User::where('type', 'supplier')->get();
        return view('admin.items.item-orders.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('admin.itemorder.store');

        $request->validate([
            'supplier_id'        => 'required|exists:users,id',
            'date'               => 'required|date',
            'items'              => 'required|array|min:1',

            'items.*.id'         => 'required|exists:items,id',
            'items.*.qty'        => 'required|numeric|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'items.*.total'      => 'required|numeric|min:0',

            'sub_total'          => 'required|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'transport_cost'     => 'nullable|numeric|min:0',
            'labour_cost'        => 'nullable|numeric|min:0',
            'grand_total'        => 'required|numeric|min:0',
        ]);

        $supplier = User::findOrFail($request->supplier_id);

        $order = ItemOrder::create([
            'date'                 => $request->date,
            'reference_invoice_no' => $request->reference_invoice_no,
            'supplier_id'          => $supplier->id,

            'subtotal'             => $request->sub_total,
            'discount'             => $request->discount ?? 0,
            'transport_cost'       => $request->transport_cost ?? 0,
            'labour_cost'          => $request->labour_cost ?? 0,
            'totalamount'          => $request->grand_total,

            'previous_due'         => $supplier->payable($supplier->id),
            'payment_status'       => 'Unpaid',
            'status'               => 'Active',
            'entry_id'             => auth('admin')->id(),
        ]);

        $order->iid = 'I000' . $order->id;
        $order->paid_amount = 0;
        $order->due_balance = $supplier->payable($supplier->id);
        $order->supplier_total_payable = $supplier->payable($supplier->id);
        $order->save();

        foreach ($request->items as $item) {

            ItemOrderDetail::create([
                'item_order_id' => $order->id,
                'item_id'       => $item['id'],
                'price'         => $item['price'],
                'qty'           => $item['qty'],
                'total'         => $item['total'],
                'stock'         => $item['qty'],
                'status'        => 'Active',
            ]);

            $product = Item::findOrFail($item['id']);
            $product->price = $item['price'];
            $product->qty   = $product->stock($product->id);
            $product->save();
        }

        session()->forget('items');
        session()->forget('customer');

        $notify[] = ['success', 'Item Order created successfully'];

        return to_route('admin.items.itemOrder.show', $order->id)
            ->withNotify($notify);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('admin.itemorder.show');
        $data['itemorder'] = ItemOrder::find($id);
        $data['itempayments'] = ItemOrderPayment::where('item_order_id', $id)->get();
        return view('admin.items.item-orders.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('admin.itemorder.edit');

        $data['itemorder'] = ItemOrder::find($id);
        $data['items'] = Item::get();
        $data['suppliers'] = User::where('type', 'supplier')->get();
        return view('admin.items.item-orders.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('admin.itemorder.update');

        DB::beginTransaction();

        try {

            $order = ItemOrder::where('id', $id)
                ->where('payment_status', 'Unpaid')
                ->first();

            if (!$order) {
                $notify[] = ['error', 'Order not found or already paid'];
                return back()->withNotify($notify);
            }

            $order->update([
                'date'               => $request->date ?? now()->format('Y-m-d'),
                'reference_invoice_no' => $request->reference_invoice_no,
                'supplier_id'        => $request->supplier_id,
                'subtotal'           => $request->sub_total ?? 0,
                'discount'           => $request->discount ?? 0,
                'transport_cost'     => $request->transport_cost ?? 0,
                'labour_cost'        => $request->labour_cost ?? 0,
                'totalamount'        => $request->grand_total ?? 0,
                'status'             => 'Active',
                'entry_id'           => auth('admin')->id(),
            ]);

            if (!$order->iid) {
                $order->iid = 'I000' . $order->id;
                $order->save();
            }

            $order->itemOrderDetail()->delete();


            foreach ($request->items as $item) {

                $productId = $item['id'] ?? null;
                $qty       = (float) ($item['qty'] ?? 0);
                $price     = (float) ($item['price'] ?? 0);

                if (!$productId || $qty <= 0 || $price <= 0) {
                    continue;
                }

                $total = round($qty * $price, 2);

                ItemOrderDetail::create([
                    'item_order_id' => $order->id,
                    'item_id'       => $productId,
                    'price'         => $price,
                    'qty'           => $qty,
                    'total'         => $total,
                    'stock'         => $qty,
                    'status'        => 'Active',
                ]);

                /* UPDATE PRODUCT STOCK */
                $product = Item::find($productId);
                if ($product) {
                    $product->qty = $product->stock($product->id);
                    $product->save();
                }
            }


            session()->forget(['items', 'customer']);

            DB::commit();

            $notify[] = ['success', 'Item Order updated successfully'];
            return redirect()
                ->route('admin.items.itemOrder.index')
                ->withNotify($notify);
        } catch (\Throwable $e) {

            DB::rollBack();

            $notify[] = ['error', 'Something went wrong: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admin.itemorder.destroy');

        $item = ItemOrder::find($id);
        if (!$item) {
            $notify[] = ['error', "Item not found"];
            return back()->withNotify($notify);
        }
        ItemOrderDetail::where('item_order_id', $id)->delete();
        $item->delete();

        // Clear specific sessions
        session()->forget('items');
        session()->forget('customer');


        $notify[] = ['success', "Item deleted successfully"];
        return back()->withNotify($notify);
    }

    public function itemOrderDetailDestroy($id)
    {
        Gate::authorize('admin.itemorder.detail.destroy');

        $item = ItemOrderDetail::find($id);
        if (!$item) {
            $notify[] = ['error', "Item not found"];

            return back()->withNotify($notify);
        }

        $itemOrder = ItemOrder::find($item->item_order_id);

        $itemOrder->totalamount = $itemOrder->totalamount - $item->total;
        $itemOrder->save();

        $item->delete();

        // Clear specific sessions
        session()->forget('items');
        session()->forget('customer');



        $notify[] = ['success', "Item deleted successfully"];
        return back()->withNotify($notify);
    }


    public function printinvoice($id)
    {
        Gate::authorize('admin.itemorder.printinvoice');

        $data['order'] = ItemOrder::find($id);
        $pdf = PDF::loadView('admin.items.item-orders.invoice', $data);
        return $pdf->stream('invoice.pdf');
    }
}
