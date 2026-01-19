<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Account\OrderPayment;
use App\Models\Account\OrderSupplierPayment;
use App\Models\Order\Order;
use App\Models\Order\Purchse;
use App\Traits\PrintTrait;
use Illuminate\Http\Request;
use App\Traits\ProcessByDate;

class PurcheseSaleController extends Controller
{

    use ProcessByDate, PrintTrait;
    /**
     * Display the purchase and sale report index page.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function purchasesell(Request $request)
    {
        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];

        $data['total_purchase'] = $this->getTotalPurchase($request);
      
        $data['purchese_discount'] = $this->getPurcheseDiscount($request);
        $data['purches_due'] = $this->getPurchesDue($request);
        $data['total_sale'] = $this->getSale($request);
        
        $data['sale_due'] = $this->getOrderDue($request);

        if(isset($request->print_type) && $request->print_type != ''){
            return $this->print($request, 'purchasesell', $data);
        }

        return view('report.purchasesell.index', $data);
    }


    protected function getTotalPurchase($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $purchase = Purchse::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $purchase;
    }


    
    protected function getPurcheseDiscount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $purchase = Purchse::whereBetween('created_at', [$from, $to])->sum('discount');
        return $purchase;
    }

    protected function getPurchesDue($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $payment = OrderSupplierPayment::whereBetween('created_at', [$from, $to])->sum('amount');
        $order = Purchse::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $payment - $order;
    }


    protected function getSale($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $amount = Order::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $amount;
    }

 

    protected function getOrderDiscount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $amount = Order::whereBetween('created_at', [$from, $to])->sum('discount_amount');
        return $amount;
    }
 

    protected function getOrderDue($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $payment = OrderPayment::whereBetween('created_at', [$from, $to])->sum('amount');
        $order = Order::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $payment - $order;
    }

}
