<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DailyItemReportService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyItemReportExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;

class DailyItemReportController extends Controller
{
    public function dailyItemReport(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->subDays(1)->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');
        $item_category = $request->item_category;
        $item_id = $request->item_id;
        $dailyServiceReport = new DailyItemReportService(start_date: $start_date, end_date: $end_date, item_category: $item_category, item_id: $item_id);
        $data = $dailyServiceReport->getReports();

        $data['start_date'] = $start_date;

        $data['end_date'] = $end_date;

        $data['item_category'] = $item_category;

        $data['item_id'] = $item_id;

        if($request->ajax())
        {
            if($request->pdf == 1){
                return $this->downloadPDF($request, $data);
            }

            return response()->json([
                'status' => 'success',
                'start_date' => $start_date,
                'end_date' => $end_date,
                'viewData' => view('admin.reports.includes.daily-item-report-table', $data)->render(),
            ], 200);
        }

        if ($request->has('export')) {
            try {
                return Excel::download(
                    new DailyItemReportExport($data),
                    'daily_item_report_'.Carbon::now()->format('YmdHis').'.xlsx',
                    \Maatwebsite\Excel\Excel::XLSX
                );
            } catch (\Exception $e) {
                return back()->withError('Export failed: '.$e->getMessage());
            }
        }


        if($request->has('pdf')){

           return $this->hasDownloadPDF($request, $data);
        }

        $data['categories'] = $dailyServiceReport->getItemCategory();

        return view('admin.reports.daily-item-report', $data);
    }



    protected function hasDownloadPDF($request, $data)
    {
        if($request->has('pdf')) {
            ini_set('max_execution_time', 3000);

           //$pdf = app('dompdf.wrapper');

           $pdf = PDF::loadView('admin.reports.includes.daily-report-item-print', $data);
           //$pdf->loadView('admin.reports.includes.daily-report-item-print', $data);

            $filename = 'daily_item_report_' . now()->format('YmdHis') . '.pdf';
            return $pdf->stream($filename);
        }
    }



    protected function downloadPDF(Request $request, $data)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 30000);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.reports.includes.daily-report-item-print', $data);
        $pdf->setPaper('A4', 'landscape');

        $timestamp = Carbon::now()->format('YmdHis');
        $filename = "daily_item_report_{$timestamp}.pdf";
        $tempPath = storage_path("app/temp_pdfs/{$filename}");

        if (!file_exists(storage_path('app/temp_pdfs'))) {
            mkdir(storage_path('app/temp_pdfs'), 0755, true);
        }

        file_put_contents($tempPath, $pdf->output());

        register_shutdown_function(function() use ($tempPath) {
            if (file_exists($tempPath)) {
                $fileCreated = filemtime($tempPath);
                if ((time() - $fileCreated) >= 600) {
                    @unlink($tempPath);
                }
            }
        });

        return response()->file($tempPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

}
