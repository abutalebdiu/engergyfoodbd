<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ProcessByDate;
use App\Models\User;
use App\Models\Account\PurchaseReturnPayment;
use App\Models\Order\Order;
use App\Models\Order\OrderReturn;
use App\Models\Order\PurchaseReturn;
use App\Models\Order\Purchse;
use App\Traits\CustomerCalculateTrait;
use App\Traits\SupplierCalculationTrait;
use App\Traits\PrintTrait;
use Illuminate\Support\Facades\Gate;

class RegisterReportController extends Controller
{
    use ProcessByDate, CustomerCalculateTrait, SupplierCalculationTrait, PrintTrait;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function registerreport(Request $request)
    {
       

        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];

        $data['users'] = $this->getUsers($request);

        $data['registers'] = $this->getRegisters($request);

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'register', $data);
        }

        return view('report.registerreport.index', $data);
    }

    protected function getUsers($request)
    {
        $query = User::query();

        return $query->get();
    }

    protected function getRegisters($request)
    {

        $query = User::whereBetween('created_at', [$this->from, $this->to]);


        if (!empty($request->user_id) && $request->user_id != 'all') {
            $query->where('id', $request->user_id);
        }


        if (!empty($request->status) && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->paginate($request->page_size ?? 10)->appends(request()->query());

        // Iterate over the users and append additional calculated fields
        foreach ($users as $user) {

            $user->register_open_date_time = $user->created_at->format('d-m-Y H:i');
            $user->register_closed_date_time = $user->status == 0 ? $user->updated_at->format('d-m-Y H:i') : null;
            $user->total_purchase = $this->getTotalPurchase($request, $user->id) ?? 0;
            $user->total_purchase_return = $this->getPurchesReturn($request, $user->id) ?? 0;
            $user->total_sale = $this->getSale($request, $user->id) ?? 0;
            $user->total_sale_return = $this->getSaleReturn($request, $user->id) ?? 0;
            $user->opening_balance = $this->openingDue($request, $user->id) ?? 0;
        }

        return $users;
    }



    protected function getTotalPurchase($request, $id)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];
        return Purchse::where('supplier_id', $id)->whereBetween('created_at', [$from, $to])->sum('totalamount');
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
        $to = $this->processByDateRange($request)[1];

        return Order::where('customer_id', $id)->whereBetween('created_at', [$from, $to])->sum('totalamount');
    }

    protected function getSaleReturn($request, $id)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];
        return OrderReturn::where('customer_id', $id)->whereBetween('created_at', [$from, $to])->sum('totalamount');
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
