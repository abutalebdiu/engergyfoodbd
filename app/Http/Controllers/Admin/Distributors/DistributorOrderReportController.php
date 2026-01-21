<?php

namespace App\Http\Controllers\Admin\Distributors;

use PDF;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Distribution\Distribution;

class DistributorOrderReportController extends Controller
{
    public function index(Request $request)
    {
        $data['distributors'] = Distribution::where('status', 'Active')->get();

        $data['start_date'] = $request->start_date ?? date('Y-m-d');
        $data['end_date']   = $request->end_date ?? date('Y-m-d');

        $query = Order::join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->selectRaw('
                orders.date,
                orders.distribution_id,
                SUM(order_details.qty) as total_qty,
                SUM(order_details.amount) as total_amount
            ')
            ->with('distribution')
            ->whereDate('orders.date', '>=', $data['start_date'])
            ->whereDate('orders.date', '<=', $data['end_date'])
            ->groupBy('orders.date', 'orders.distribution_id')
            ->orderBy('orders.date', 'DESC')
            ->whereNotNull('distribution_id');

        if ($request->distribution_id) {
            $query->where('orders.distribution_id', $request->distribution_id);
        }

        if ($request->has('pdf')) {

            $data['distributor_orders'] = $query
                ->get();

            return $this->downloadPDF($request, $data);
        }


        if ($request->ajax()) {

            $data['distributor_orders'] = $query
                ->paginate(10);

            return view('admin.distributors.distributor-order-report.table', $data)->render();
        }

        $data['distributor_orders'] = $query
            ->paginate(10);

        return view('admin.distributors.distributor-order-report.index', $data);
    }


    protected function downloadPDF($request, $data)
    {
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.distributors.distributor-order-report.pdf', $data);
            $filename = 'distributor_order_report_' . now()->format('YmdHis') . '.pdf';
            return $pdf->stream($filename);
        }
    }


    public function show(Request $request)
    {
        $date = $request->date;
        $distributionId = $request->distribution_id;

        $details = Order::join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select(
                'order_details.product_id',
                'products.name as product_name',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.amount) as total_amount')
            )
            ->whereDate('orders.date', $date)
            ->where('orders.distribution_id', $distributionId)
            ->groupBy('order_details.product_id', 'products.name')
            ->orderBy('products.name')
            ->get();

        $grandTotal = Order::join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->whereDate('orders.date', $date)
            ->where('orders.distribution_id', $distributionId)
            ->sum('order_details.amount');


        if($request->type == 'pdf'){
            $data['details'] = $details;
            $data['date'] = $date;
            $data['distributionId'] = $distributionId;
            $data['distribtion_name'] = Distribution::find($distributionId)->name ?? '';
            $data['grandTotal'] = $grandTotal;
            return $this->downloadShowPdf($request, $data);
        }

        return view(
            'admin.distributors.distributor-order-report.details',
            compact('details', 'date', 'distributionId', 'grandTotal')
        );
    }



    public function downloadShowPdf($request, $data)
    {

        $pdf = PDF::loadView('admin.distributors.distributor-order-report.showpdf', $data);
        $filename = 'distributor_order_report_' . now()->format('YmdHis') . '.pdf';
        return $pdf->stream($filename);
        
    }
}
