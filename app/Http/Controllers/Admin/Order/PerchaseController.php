<?php

namespace App\Http\Controllers\Admin\Order;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order\Purchse;
use App\Models\Product\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Order\PurchaseDetail;
use App\Models\Product\ProductStock;
use Illuminate\Support\Facades\Gate;

class PerchaseController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.purchse.list');

        $data['orders'] = Purchse::active()->latest()->get();
        return view('admin.orders.purchase.view', $data);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.purchse.create');
        return view('admin.orders.purchase.create');
    }

    public function searchProduct(Request $request)
    {
        $searchTerm = $request->input('search');

        if (!is_string($searchTerm)) {
            return response()->json(['error' => 'Invalid search term.'], 400);
        }

        try {
            $products = Product::where('name', 'like', '%' . $searchTerm . '%')
                ->get(['id', 'name']);

            $formattedProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->name,
                ];
            });

            return response()->json($formattedProducts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while processing your request.', 'message' => $e->getMessage()], 500);
        }
    }

    public function searchSupplier(Request $request)
    {
        $searchTerm = $request->input('search');

        if (!is_string($searchTerm)) {
            return response()->json(['error' => 'Invalid search term.'], 400);
        }

        try {
            $users = User::where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('mobile', 'like', '%' . $searchTerm . '%')
                ->where('type', 'supplier')
                ->get(['id', 'name']);

            $formattedUsers = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name,
                ];
            });

            return response()->json($formattedUsers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while processing your request.', 'message' => $e->getMessage()], 500);
        }
    }


    public function addProduct(Request $request, $id)
    {
        $product = Product::find($id);

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
        $products = session()->get('products', []);

        // Check if the product ID is already in the session
        if (!in_array($product->id, array_column($products, 'id'))) {
            // Add the product to the session if it's not already there
            // previous qty unset
            unset($product['qty']);
            // set product qty to 1
            $product['qty'] = 1;
            session()->push('products', $product);
        } else {
            return response()->json(['error' => 'Product already added.'], 400);
        }

        return response()->json(['success' => 'Product added successfully.']);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }


        // Retrieve the existing products from the session, or initialize an empty array if none exist
        $products = session()->get('products', []);


        // Check if the product ID is already in the session
        $key = array_search($id, array_column($products, 'id'));
        if ($key !== false) {
            unset($products[$key][$request->type]);
            $products[$key][$request->type] = $request->val;
        }

        session()->forget('products');

        // Add the remaining products back to the session
        session()->put('products', $products);

        return response()->json(['success' => 'Product updated successfully.']);
    }


    public function getProducts()
    {

        $products = session()->get('products');


        if ($products) {
            $html = view('admin.orders.purchase.inc.response_table_body', compact('products'))->render();
            return response()->json(['html' => $html]);
        } else {
            return response()->json(['error' => 'Add product first.'], 404);
        }
    }

    public function removeProduct(Request $request, $id)
    {

        $products = session()->get('products');


        if (!$products) {
            return response()->json(['error' => 'No products found.'], 404);
        }

        // Remove the product from the session

        $key = array_search($id, array_column($products, 'id'));

        if ($key !== false) {
            unset($products[$key]);
        }

        session()->forget('products');


        // Add the remaining products back to the session

        session()->put('products', $products);

        return response()->json(['success' => 'Product removed successfully.']);
    }


    public function store(Request $request)
    {
        Gate::authorize('admin.purchse.store');

        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:users,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);


        try {

            $purchse = Purchse::create([
                "date" => $request->date ? $request->date : date('Y-m-d'),
                "reference_invoice_no" => $request->reference_invoice_no,
                "supplier_id" => $request->supplier_id,
                "sub_total"   => $request->sub_total,
                "discount"    => $request->discount,
                "vat"         => $request->vat,
                "ait"         => $request->ait,
                "transport_cost"         => $request->transport_cost,
                "totalamount" => $request->grand_total,
                "payment_status" => "Unpaid",
                "status" => "Active",
                "entry_id" => auth('admin')->user()->id
            ]);

            $purchse->pid = "P000" . $purchse->id;
            $purchse->save();


            foreach ($request->product_id as $key => $value) {

                $product = Product::find($value);
                $product->qty = $product->qty + $request->qty[$key];
                $product->save();

                PurchaseDetail::create([
                    "purchase_id"       => $purchse->id,
                    "product_id"        => $request->product_id[$key],
                    "price"             => $request->price[$key],
                    "qty"               => $request->qty[$key],
                    "amount"            => floor($request->price[$key] * $request->qty[$key]),
                    "paymentstatus"     => 'Unpaid'
                ]);

                if ($request->qty[$key]) {
                    $productstock = new ProductStock();
                    $productstock->product_id = $value;
                    $productstock->stock_id   = 1;
                    $productstock->qty        = $request->qty[$key];
                    $productstock->entry_id   = auth('admin')->user()->id;
                    $productstock->type       = 'purchase';
                    $productstock->status     = 'Plus';
                    $productstock->save();
                }
            }

            // session clear
            session()->forget('products');

            // customer session clear
            session()->forget('customer');


            $notify[] = ['success', "Purchse created successfully"];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function show($id)
    {
        Gate::authorize('admin.purchse.show');

        $purchse = Purchse::find($id);
        // $data['type'] = $purchse->type;
        return view('admin.orders.purchase.show', compact('purchse'));
    }

    public function edit($id)
    {
        Gate::authorize('admin.purchse.edit');

        $purchse = Purchse::find($id);
        if (!$purchse) {
            $notify[] = ['error', "An error occurred while processing your request."];
            return back()->withNotify($notify);
        }

        return view('admin.orders.purchase.edit', compact('purchse'));
    }

    public function update(Request $request, Purchse $purchse)
    {
        Gate::authorize('admin.purchse.update');

        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required',
            'product_id' => 'required|array',
            'purchase_price' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'total' => 'required|array',
        ]);

        $purchse->update(array_merge(
            $request->except([
                '_token',
                'product_id',
                'total',
                'grand_total'

            ]),
            [
                "date" => $request->date ? $request->date : date('Y-m-d'),
                "reference_invoice_no" => $request->reference_invoice_no,
                "supplier_id" => $request->supplier_id,
                "sub_total"   => $request->sub_total,
                "discount"    => $request->discount,
                "vat"         => $request->vat,
                "ait"         => $request->ait,
                "transport_cost"  => $request->transport_cost,
                "totalamount" => $request->grand_total,
                "payment_status" => "Unpaid",
                'edit_id' => auth('admin')->user()->id,
                'edit_at' => now(),
            ]
        ));



        $purchse->purchasedetail()->delete();

        $input = $request->all();

        foreach ($input['product_id'] as $key => $productId) {
            PurchaseDetail::create([
                'purchase_id' => $purchse->id,
                'product_id' => $input['product_id'][$key],
                'purchase_price' => $input['purchase_price'][$key],
                'qty' => $input['qty'][$key],
                'price' => $input['price'][$key],
                'amount' => $input['total'][$key],
                'entry_id' => auth('admin')->user()->id,
                'status' => 'Active',
            ]);
        }


        // session clear
        session()->forget('products');

        // customer session clear
        session()->forget('customer');

        $notify[] = ['success', 'Purchase successfully Updated'];
        return to_route('admin.purchase.index', ['type' => $purchse->type])->withNotify($notify);
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('admin.purchse.destroy');

        PurchaseDetail::where('purchase_id', $id)->delete();
        $purchse = Purchse::find($id);
        $purchse->delete();
        $notify[] = ['success', "Purchase deleted successfully"];
        return back()->withNotify($notify);
    }


    public function printinvoice($id)
    {
        Gate::authorize('admin.perchase.invoice.print');

        $data['order'] = Purchse::find($id);
        $pdf = Pdf::loadView('admin.orders.purchase.invoice', $data);
        return $pdf->stream('invoice.pdf');

        // return view('admin.orders.orders.invoice',$data);
    }
}
