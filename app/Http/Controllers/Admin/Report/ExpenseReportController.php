<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ProcessByDate;

class ExpenseReportController extends Controller
{
    use ProcessByDate;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function expensereport(Request $request)
    {
        $data['range_date'] = $this->processByDateRange($request)[0] . 'to' . $this->processByDateRange($request)[1];

        if (isset($request->print_type) && $request->print_type != '') {
            return $this->print($request, 'expense', $data);
        }

        return view('report.expensereport.index', $data);
    }
}
