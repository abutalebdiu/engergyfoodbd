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
            ->selectRaw("
                quotations.date,
                quotations.distribution_id,
                SUM(quotation_details.qty) AS total_qty,
                SUM(quotation_details.amount) AS total_amount,
                SUM(quotation_details.dc_amount) AS dc_amount,
                SUM(quotation_details.dc_product_commission) AS dc_product_commission,
                SUM(quotation_details.product_commission) AS product_commission
            ")
            ->with('distribution')
            ->whereDate('quotations.date', '>=', $data['start_date'])
            ->whereDate('quotations.date', '<=', $data['end_date'])
            ->whereNotNull('quotations.distribution_id')
            ->groupBy('quotations.date', 'quotations.distribution_id')
            ->orderByDesc('quotations.date');

        if (!empty($request->distribution_id)) {
            $query->where('quotations.distribution_id', $request->distribution_id);
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
                DB::raw('SUM(quotation_details.amount) as total_amount'),
                DB::raw('SUM(quotation_details.product_commission) as product_commission'),
                DB::raw('SUM(quotation_details.dc_price) as dc_price'),
                DB::raw('SUM(quotation_details.dc_amount) as dc_amount'),
                DB::raw('SUM(quotation_details.dc_product_commission) as dc_product_commission'),
                DB::raw('SUM(quotation_details.price) as price')
            )
            ->whereDate('quotations.date', $date)
            ->where('quotations.distribution_id', $distributionId)
            ->groupBy('quotation_details.product_id', 'products.name')
            ->orderBy('products.name')
            ->get();

        $grandTotal = Quotation::join('quotation_details', 'quotation_details.quotation_id', '=', 'quotations.id')
            ->whereDate('quotations.date', $date)
            ->where('quotations.distribution_id', $distributionId)
            ->selectRaw("
                SUM(quotation_details.amount) as total_amount,
                SUM(quotation_details.product_commission) as product_commission,
                SUM(quotation_details.dc_price) as dc_price,
                SUM(quotation_details.dc_amount) as dc_amount,
                SUM(quotation_details.dc_product_commission) as dc_product_commission,
                SUM(quotation_details.price) as price
            ")
            ->first();


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
