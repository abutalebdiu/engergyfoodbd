<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Traits\ProcessByDate;
use App\Traits\PrintTrait;
use Illuminate\Support\Facades\Gate;

class ItemStockReportController extends Controller
{
    use ProcessByDate, PrintTrait;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function itemstockreport(Request $request)
    {


        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];
        $data['itemswithcategories'] = $this->getItems($request);
        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'itemstock', $data);
        }
        return view('report.stockreport.itemstockreport', $data);
    }



    protected function getItems($request)
    {

        $query = Item::query()->with('category');
        //   return $query->whereBetween('created_at', [$this->from, $this->to])->get();
        return $query->get()->groupby('item_category_id');
    }
}
