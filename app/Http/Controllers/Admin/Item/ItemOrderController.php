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
        }
        else {
            $query->where('created_at', '>=', Carbon::now()->subHours(100));
        }




        if($request->ajax()){

            $data['items'] = $query->latest()->paginate(200);

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


    public function addProduct(Request $request, $id)
    {
        $product = Item::find($id);


        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        // find customer

        if ($request->user_id) {
            $user = User::find($request->user_id);
            if ($user) {
                session()->put('customer', $user);
            }
        }

        // Retrieve the existing products from the session, or initialize an empty array if none exist
        $items = session()->get('items') ?? ['id' => $id, 'qty' => 1];

        // Check if the product ID is already in the session
        if (!in_array($product->id, array_column($items, 'id'))) {
            // Add the item to the session if it's not already there
            // previous qty unset
            unset($product['qty']);
            // set product qty to 1
            $product['qty'] = 1;
            session()->push('items', $product);
        } else {
            return response()->json(['error' => 'Item already added.'], 400);
        }

        return response()->json(['success' => 'Product added successfully.']);
    }

    public function updateProduct(Request $request, $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['error' => 'Item not found.'], 404);
        }

        // Retrieve the existing products from the session, or initialize an empty array if none exist
        $items = session()->get('items', []);
        // Check if the product ID is already in the session

        $key = array_search($id, array_column($items, 'id'));
        if ($key !== false) {
            unset($items[$key][$request->type]);
            $items[$key][$request->type] = $request->val;
        }

        session()->forget('items');

        // Add the remaining products back to the session
        session()->put('items', $items);

        return response()->json(['success' => 'Items updated successfully.']);
    }


    public function getProducts()
    {

        $items = session()->get('items');


        if ($items) {
            $html = view('admin.items.item-orders.inc.response_table_body', compact('items'))->render();
            return response()->json(['html' => $html]);
        } else {
            return response()->json(['error' => 'Add item first.'], 404);
        }
    }

    public function removeProduct(Request $request, $id)
    {

        $items = session()->get('items');


        if (!$items) {
            return response()->json(['error' => 'No items found.'], 404);
        }

        // Remove the items from the session

        $key = array_search($id, array_column($items, 'id'));

        if ($key !== false) {
            unset($items[$key]);
        }

        session()->forget('items');

        // Add the remaining items back to the session

        session()->put('items', $items);

        return response()->json(['success' => 'Items removed successfully.']);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('admin.itemorder.create');

        $data['itemcategories'] = ItemCategory::get();
        $data['items'] = Item::orderby('name', 'asc')->get();
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
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:users,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        // return $request->product_id[0];
        $supplier = User::find($request->supplier_id);
        // DB::beginTransaction();
        // try {
        //  Create ItemOrder
        $order = ItemOrder::create([
            "date" => $request->date ?? date('Y-m-d'),
            "reference_invoice_no" => $request->reference_invoice_no,
            "supplier_id" => $request->supplier_id,
            "subtotal" => $request->sub_total,
            "discount" => $request->discount,
            "transport_cost" => $request->transport_cost,
            "labour_cost"  => $request->labour_cost,
            "totalamount"  => $request->grand_total,
            "previous_due" => $supplier->payable($supplier->id),
            "payment_status" => "Unpaid",
            "status" => "Active",
            "entry_id" => auth('admin')->user()->id,
        ]);

        // Set a custom order ID
        $order->iid = "I000" . $order->id;
        $order->due_balance = $supplier->payable($supplier->id);
        $order->paid_amount = 0;
        $order->supplier_total_payable = $supplier->payable($supplier->id);
        $order->save();

        foreach ($request->product_id as $key => $productId) {
            if (!isset($request->qty[$key], $request->amount[$key])) {
                $notify[] = ['error', "Incomplete data for product at index $key"];
                return back()->withNotify($notify);
            }
            $detail =  ItemOrderDetail::create([
                "item_order_id" => $order->id,
                "item_id"       => $productId,
                "price"         => round($request->amount[$key] / $request->qty[$key], 2),
                "qty"           => $request->qty[$key],
                "total"         => $request->amount[$key],
                "stock"         => $request->qty[$key],
                "status"        => 'Active',
            ]);

            $product = Item::findOrFail($productId);
            $product->price = $detail->price;
            $product->qty = $product->stock($product->id);
            $product->save();
        }


        // Clear specific sessions
        session()->forget('items');
        session()->forget('customer');

        // Notify success
        $notify[] = ['success', "Item Order created successfully"];

        //   DB::commit();
        return to_route('admin.items.itemOrder.show', $order->id)->withNotify($notify);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $notify[] = ['error', "An error occurred while processing your request: " . $e->getMessage()];
        //     return back()->withNotify($notify);
        // }
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
            // Find the order
            $order = ItemOrder::where('id', $id)->where('payment_status', 'Unpaid')->first();

            if (!$order) {
                $notify[] = ['error', 'Order not found or is already paid.'];
                return back()->withNotify($notify);
            }

            // Update the order
            $order->update([
                "date" => $request->date ?? date('Y-m-d'),
                "reference_invoice_no" => $request->reference_invoice_no,
                "supplier_id" => $request->supplier_id,
                "subtotal" => $request->sub_total,
                "discount" => $request->discount,
                "transport_cost" => $request->transport_cost,
                "labour_cost" => $request->labour_cost,
                "totalamount" => $request->grand_total,
                "payment_status" => "Unpaid",
                "status" => "Active",
                "entry_id" => auth('admin')->user()->id,
            ]);

            // Set a custom order ID
            $order->iid = "I000" . $order->id;
            $order->save();

            // Process the products

            $order->itemOrderDetail()->each(function ($itemOrderDetail) {
                $itemOrderDetail->delete();
            });


            foreach ($request->product_id as $key => $productId) {
                if (!isset($request->qty[$key], $request->price[$key])) {
                    $notify[] = ['error', "Incomplete data for product at index $key"];
                    return back()->withNotify($notify);
                }


                ItemOrderDetail::create([
                    "item_order_id" => $order->id,
                    "item_id" => $productId,
                    "price" => $request->price[$key],
                    "qty" => $request->qty[$key],
                    "total" => floor($request->price[$key] * $request->qty[$key]),
                    "stock" => $request->qty[$key],
                    "status" => 'Active',
                ]);

                $product = Item::findOrFail($productId);
                $product->qty = $product->stock($product->id);
                $product->save();

                // ItemOrderDetail::updateOrCreate(
                //     [
                //         "id" => $request->order_detial_id[$key],
                //         "item_order_id" => $order->id,
                //         "item_id" => $productId,
                //     ],
                //     [
                //         "price" => $request->price[$key],
                //         "qty" => $request->qty[$key],
                //         "total" => round($request->price[$key] * $request->qty[$key]),
                //         "stock" => $request->qty[$key],
                //         "status" => 'Active',
                //     ]
                // );
            }

            // Clear specific sessions
            session()->forget('items');
            session()->forget('customer');

            // Notify success
            $notify[] = ['success', "Item Order updated successfully"];
            DB::commit();
            return redirect()->route('admin.items.itemOrder.index')->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
            $notify[] = ['error', "An error occurred while processing your request: " . $e->getMessage()];
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
