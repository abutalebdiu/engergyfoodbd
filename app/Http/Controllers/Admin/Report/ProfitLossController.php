<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\PurchaseReturnPayment;
use App\Models\Order\Order;
use App\Models\Order\OrderReturn;
use App\Models\Order\Purchse;
use App\Models\Product\Product;
use App\Models\Product\ProductBrand;
use App\Models\Product\ProductCategory;
use App\Traits\PrintTrait;
use App\Traits\ProcessByDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfitLossController extends Controller
{

    use ProcessByDate, PrintTrait;

    public function profitloss(Request $request)
    {


        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];
        $data['opening_balance'] = $this->getOpeningBalance($request);
        $data['total_purchase'] = $this->getTotalPurchase($request);
        $data['total_purchase_vat'] = $this->getTotalPurchaseVat($request);
        $data['total_purchase_discount'] = $this->getTotalPurchaseDiscount($request);
        $data['total_purchase_return'] = $this->getTotalPurchaseReturn($request);
        $data['total_order_amount'] = $this->getOrderAmount($request);
        $data['total_order_vat'] = $this->getOrderVat($request);
        $data['total_order_discount'] = $this->getOrderDiscount($request);
        $data['total_order_return'] = $this->getOrderReturnAmount($request);
        $data['total_clossing'] = $this->getClossingAmount($request);
        $data['all_products'] = $this->getAllProducts($request);
        $data['all_brands'] = $this->getBrands($request);
        $data['all_categories'] = $this->getCategories($request);

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'profitlos', $data);
        }

        return view('report.profitloss.index', $data);
    }

    protected function getOpeningBalance(Request $request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $balance = Account::whereBetween('created_at', [$from, $to])->sum('opening_balance');
        return $balance;
    }

    protected function getTotalPurchase($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $purchase = Purchse::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $purchase;
    }

    protected function getTotalPurchaseVat($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $purchase = Purchse::whereBetween('created_at', [$from, $to])->sum('vat');
        return $purchase;
    }

    protected function getTotalPurchaseDiscount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $purchase = Purchse::whereBetween('created_at', [$from, $to])->sum('discount');
        return $purchase;
    }

    protected function getTotalPurchaseReturn($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $total = PurchaseReturnPayment::whereBetween('created_at', [$from, $to])->sum('amount');
        return $total;
    }

    protected function getClossingAmount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $balance = Account::whereBetween('created_at', [$from, $to])->sum('main_balance');
        return $balance;
    }

    protected function getOrderAmount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $totalAmount = Order::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $totalAmount;
    }

    protected function getOrderDiscount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $totalAmount = Order::whereBetween('created_at', [$from, $to])->sum('discount_amount');
        return $totalAmount;
    }

    protected function getOrderVat($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $totalAmount = Order::whereBetween('created_at', [$from, $to])->sum('vat_amount');
        return $totalAmount;
    }

    protected function getOrderReturnAmount($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $totalAmount = OrderReturn::whereBetween('created_at', [$from, $to])->sum('totalamount');
        return $totalAmount;
    }

    protected function getAllProducts($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $products = Product::whereBetween('created_at', [$from, $to])->get();
        return $products;
    }

    protected function getCategories($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $categories = ProductCategory::whereBetween('created_at', [$from, $to])->get();
        return $categories;
    }

    protected function getBrands($request)
    {
        $from = $this->processByDateRange($request)[0];
        $to = $this->processByDateRange($request)[1];

        $brands = ProductBrand::whereBetween('created_at', [$from, $to])->get();
        return $brands;
    }
}
