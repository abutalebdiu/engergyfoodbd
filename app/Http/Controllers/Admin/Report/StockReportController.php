<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;

use Illuminate\Http\Request;
use App\Traits\ProcessByDate;
use App\Traits\PrintTrait;
use Illuminate\Support\Facades\Gate;

class StockReportController extends Controller
{
    use ProcessByDate, PrintTrait;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function stockreport(Request $request)
    {

        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];
        $data['productswithdepartments'] = $this->getProducts($request);
        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'stock', $data);
        }
        return view('report.stockreport.index', $data);
    }



    protected function getProducts($request)
    {

        $query = Product::query()->with('department');
        //   return $query->whereBetween('created_at', [$this->from, $this->to])->get();
        return $query->get()->groupby('department_id');
    }
}
