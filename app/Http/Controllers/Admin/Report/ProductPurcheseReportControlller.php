<?php

namespace App\Http\Controllers\Admin\Report;

use App\Traits\PrintTrait;
use Illuminate\Http\Request;
use App\Models\Order\Purchse;
use App\Traits\ProcessByDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ProductPurcheseReportControlller extends Controller
{
    use ProcessByDate, PrintTrait;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function productpurchasereport(Request $request)
    {


        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];

        $data['purchases'] = $this->getProducts($request);

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'productpurchase', $data);
        }

        return view('report.productpurchasereport.index', $data);
    }



    protected function getProducts($request)
    {
        $query = Purchse::query()->with(['supplier']);

        if (isset($request->purchase_id) && $request->purchase_id !== '') {
            $query->where('pid', $request->purchase_id);
        }

        $purchases = $query->whereBetween('created_at', [$this->from, $this->to])->paginate(getPaginate())->appends(request()->query());

        foreach ($purchases as $key => $purchase) {

            $quantity = 0;
            $name = null;
            $category = null;
            $brand = null;
            $code = null;
            $price = 0;

            $purchase->purchasedetail->each(function ($detail) use (&$quantity, &$name, &$category, &$brand, &$code, &$price) {
                $quantity += $detail->qty ?? 0;
                $name = $detail->product->name ?? 'N/A';
                $category = $detail->product->category->name ?? 'N/A';
                $brand = $detail->product->brand->name ?? 'N/A';
                $code = $detail->product->code ?? 'N/A';
                $price = $detail->price ?? 0;
            });

            $purchase->quantity = $quantity;
            $purchase->name = $name;
            $purchase->category = $category;
            $purchase->brand = $brand;
            $purchase->code = $code;
            $purchase->supplier_name = $purchase->supplier->name;
            $purchase->price = $price;
            $purchase->paid = $purchase->paidamount($purchase->id);

            unset($purchase->purchasedetail);
            unset($purchase->supplier);
        }

        return $purchases;
    }
}
