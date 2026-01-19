<?php

namespace App\Http\Controllers\Admin\Report;

use App\Traits\PrintTrait;
use Illuminate\Http\Request;
use App\Traits\ProcessByDate;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class StockAdjustmentController extends Controller
{
    use ProcessByDate, PrintTrait;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function stockadjustment(Request $request)
    {



        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];

        $data['products'] = $this->getProducts($request);

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'stockadjustment', $data);
        }

        return view('report.stockadjustment.index', $data);
    }




    protected function getProducts($request)
    {

        $query = Product::query();



        return $query->whereBetween('created_at', [$this->from, $this->to])->get();
    }
}
