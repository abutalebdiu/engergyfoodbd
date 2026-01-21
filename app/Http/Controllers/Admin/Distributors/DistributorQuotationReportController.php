<?php

namespace App\Http\Controllers\Admin\Distributors;

use PDF;
use Illuminate\Http\Request;
use App\Models\Order\Quotation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Distribution\Distribution;

class DistributorQuotationReportController extends Controller
{
    public function index(Request $request)
    {
        $data['distributors'] = Distribution::where('status', 'Active')->get();

        $data['start_date'] = $request->start_date ?? date('Y-m-d');
        $data['end_date']   = $request->end_date ?? date('Y-m-d');

        $query = Quotation::join('quotation_details', 'quotation_details.quotation_id', '=', 'quotations.id')
            ->selectRaw('quotations.date, quotations.distribution_id, 
                SUM(quotation_details.qty) as total_qty,
                SUM(quotation_details.amount) as total_amount')
            ->with('distribution')
            ->whereDate('quotations.date', '>=', $data['start_date'])
            ->whereDate('quotations.date', '<=', $data['end_date'])
            ->groupBy('date', 'distribution_id')
            ->orderBy('date', 'DESC')
            ->whereNotNull('distribution_id');

        if ($request->distribution_id) {
            $query->where('distribution_id', $request->distribution_id);
        }

    
        if ($request->ajax()) {
            $data['distributor_quotations'] = $query->paginate(10);
            return view('admin.distributors.distributor-quotation-report.table', $data)->render();
        }


        if($request->has('pdf')) {
            $data['distributor_quotations'] = $query->get();
            $this->downloadPDF($request, $data);
        }

        $data['distributor_quotations'] = $query->paginate(10);

        return view('admin.distributors.distributor-quotation-report.index', $data);
    }

    protected function downloadPDF($request, $data)
    {
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.distributors.distributor-quotation-report.pdf', $data);
            $filename = 'distributor_order_report_' . now()->format('YmdHis') . '.pdf';
            return $pdf->stream($filename);
        }
    }



    public function show(Request $request)
    {
        $date = $request->date;
        $distributionId = $request->distribution_id;

        $details = Quotation::join('quotation_details', 'quotation_details.quotation_id', '=', 'quotations.id')
            ->join('products', 'products.id', '=', 'quotation_details.product_id')
            ->select(
            'quotation_details.product_id',
                'products.name as product_name',
                DB::raw('SUM(quotation_details.qty) as total_qty'),
                DB::raw('SUM(quotation_details.amount) as total_amount')
            )
            ->whereDate('quotations.date', $date)
            ->where('quotations.distribution_id', $distributionId)
            ->groupBy('quotation_details.product_id', 'products.name')
            ->orderBy('products.name')
            ->get();

        $grandTotal = Quotation::join('quotation_details', 'quotation_details.quotation_id', '=', 'quotations.id')
            ->whereDate('quotations.date', $date)
            ->where('quotations.distribution_id', $distributionId)
            ->sum('quotation_details.amount');


        if ($request->type == 'pdf') {
            $data['details'] = $details;
            $data['date'] = $date;
            $data['distributionId'] = $distributionId;
            $data['distribtion_name'] = Distribution::find($distributionId)->name ?? '';
            $data['grandTotal'] = $grandTotal;
            return $this->downloadShowPdf($request, $data);
        }

        return view(
            'admin.distributors.distributor-quotation-report.details',
            compact('details', 'date', 'distributionId', 'grandTotal')
        );
    }



    public function downloadShowPdf($request, $data)
    {

        $pdf = PDF::loadView('admin.distributors.distributor-quotation-report.showpdf', $data);
        $filename = 'distributor_order_report_' . now()->format('YmdHis') . '.pdf';
        return $pdf->stream($filename);
    }
}
