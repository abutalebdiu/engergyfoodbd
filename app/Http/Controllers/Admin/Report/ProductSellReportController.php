<?php

namespace App\Http\Controllers\Admin\Report;

use App\Traits\PrintTrait;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Traits\ProcessByDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ProductSellReportController extends Controller
{
    use ProcessByDate, PrintTrait;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function productsalesreport(Request $request)
    {


        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];
        $data['orders'] = $this->getProducts($request);

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'productsale', $data);
        }

        return view('report.productsalesreport.index', $data);
    }


    protected function getProducts($request)
    {

        $query = Order::query()->whereBetween('created_at', [$this->from, $this->to])->with(['customer', 'orderdetail.order']);

        if (isset($request->order_id) && $request->order_id !== '') {
            $query->where('oid', $request->order_id);
        }

        $orders = $query->paginate(getPaginate(10))->appends(request()->query());

        foreach ($orders as $key => $order) {
            $quantity = 0;
            $name = null;
            $code = null;
            $price = 0;

            $order->orderdetail->each(function ($detail) use (&$quantity, &$name, &$code, &$price) {
                $quantity += $detail->qty ?? 0;
                $name = $detail->product->name ?? 'N/A';
                $price = $detail->price ?? 0;
            });

            $order->quantity = $quantity;
            $order->name = $name;
            $order->code = $code;
            $order->price = $price;
            $order->customer_name = $order->customer?->name ?? 'N/A';
            $order->contact = $order->customer?->mobile ?? 'N/A';
            $order->payment_method = 'Cash';

            unset($order->orderdetail);

            unset($order->customer);
        }

        return $orders;
    }
}
