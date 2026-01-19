<?php

namespace App\Http\Controllers\Admin\Report;

use App\Models\User;
use App\Traits\PrintTrait;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Order\Purchse;
use App\Traits\ProcessByDate;
use App\Models\Order\OrderReturn;
use App\Http\Controllers\Controller;
use App\Models\Order\PurchaseReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomerCalculateTrait;
use App\Traits\SupplierCalculationTrait;
use App\Models\Account\PurchaseReturnPayment;
use App\Models\Item;
use App\Models\ItemOrder;

class CustomerSupplierController extends Controller
{
    use ProcessByDate, CustomerCalculateTrait, SupplierCalculationTrait, PrintTrait;

    public function __construct() {}

    public function customersupplier(Request $request)
    {

        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];
        $data['all_contacts'] = $this->getContacts($request);
        $data['users'] = $this->getUsers($request);

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'customersupplier', $data);
        }

        return view('report.customersupplier.index', $data);
    }

    protected function getUsers($request)
    {

        $query = User::query();
        $users = $query->get();

        return $users;
    }

    public function getContacts($request)
    {
        $query = User::query();

        if ($request->filled('type')) {
            if ($request->type === 'customer') {
                $query->where('type', 'customer');
            } elseif ($request->type === 'supplier') {
                $query->where('type', 'supplier');
            }
        }

        if ($request->filled('contact') && $request->contact !== 'all') {
            $query->where('id', $request->contact);
        }

        $contacts = $query->get();


        $data = [];

        foreach ($contacts as $contact) {
            $data[] = [
                'id' => $contact->id,
                'name' => $contact->name,
                'total_purchase' => $this->getTotalPurchase($request, $contact->id) ?? 0,
                'total_purchase_return' => $this->getPurchesReturn($request, $contact->id) ?? 0,
                'total_sale' => $this->getSale($request, $contact->id) ?? 0,
                'total_sale_return' => $this->getSaleReturn($request, $contact->id) ?? 0,
                'opening_balance' => $this->openingDue($request, $contact->id) ?? 0,
                'total_due' => $this->getTotalDue($request, $contact->id),
            ];
        };

        return $data;
    }


    protected function getTotalPurchase($request, $id)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];
        return ItemOrder::where('supplier_id', $id)->whereBetween('created_at', [$from, $to])->sum('totalamount');
    }

    protected function getPurchesReturn($request, $id)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];
        return PurchaseReturn::where('supplier_id', $id)->whereBetween('created_at', [$from, $to])->sum('amount');
    }

    protected function getSale($request, $id)
    {
        $from = $this->processByDateRange($request)[0];
        $to   = $this->processByDateRange($request)[1];

        return Order::where('customer_id', $id)->whereBetween('created_at', [$from, $to])->sum('net_amount');
    }

    protected function getSaleReturn($request, $id)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];
        return OrderReturn::where('customer_id', $id)->whereBetween('created_at', [$from, $to])->sum('net_amount');
    }

    protected function openingDue($request, $id)
    {
        // Extract the date range from the request
        [$from, $to] = $this->processByDateRange($request);

        // Retrieve the user's 'opening' field where the 'created_at' is within the specified date range
        $due = User::where('id', $id)
            ->whereBetween('created_at', [$from, $to])
            ->pluck('opening')
            ->first();

        return $due;
    }




    protected function getTotalDue($request, $id)
    {
        $user = User::find($id);

        if ($user->type == 'customer') {
            return $this->getCustomerDue($request, $id);
        } elseif ($user->type == 'supplier') {
            return $this->getSupplierDue($request, $id);
        }
        return 0;
    }
}
