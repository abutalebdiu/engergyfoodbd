<?php

namespace App\Traits;

use Exception;
use PDF;
use App\Exports\Report\Report;
use Maatwebsite\Excel\Facades\Excel;

trait PrintTrait
{
    public $view_path = 'report.print';

    public function print($request, $view = 'print', $data = [])
    {
        if(isset($request->print_type) && $request->print_type == 'pdf') {
            return $this->printPdf($view, $this->getLanguage($request), $data);
        } elseif(isset($request->print_type) && $request->print_type == 'excel') {
            return $this->printExcel($view, $this->getLanguage($request), $data);
        } elseif(isset($request->print_type) && $request->print_type == 'csv') {
            return $this->printCsv($view, $this->getLanguage($request), $data);
        }
    }

    private function printPdf($view, $lang = 'en', $data = [])
    {
        try {
            $name = $view . '-' . $lang . '-' . date('Y-m-d') . '.pdf';

            $pdf = PDF::loadView($this->view_path . '.' . $lang . '.' . $view,[
                'data' => $data,
                'layout' => true,
            ]);

             return $pdf->stream($name);

        } catch (\Exception $e) {
            return $e->getMessage();
            $notify[] = ['info', "Oops! Only PDF is supported"];
            return back()->withNotify($notify);
        }
    }


    private function printExcel($view, $lang = 'en', $data = [])
    {
       try {
        $name = $view . '-' . $lang . '-' . date('Y-m-d') . '.xlsx';

        $excel_view = $this->view_path . '.' . $lang . '.' . $view;

        return Excel::download(new Report($excel_view, $data), $name);
       } catch (Exception $e) {

        $notify[] = ['info', "Oops! Only PDF is supported"];
           return back()->withNotify($notify);
       }
    }

    private function printCsv($view, $lang = 'en', $data = [])
    {
        try {
            $name = $view . '-' . $lang . '-' . date('Y-m-d') . '.csv';

            $csv_view = $this->view_path . '.' . $lang . '.' . $view;

            return Excel::download(new Report($csv_view, $data), $name);
        } catch (Exception $e) {
            $notify[] = ['info', "Oops! Only PDF is supported"];
            return back()->withNotify($notify);
        }
    }

    protected function getLanguage($request)
    {
        return isset($request->lang) ? $request->lang : 'en';
    }
}
