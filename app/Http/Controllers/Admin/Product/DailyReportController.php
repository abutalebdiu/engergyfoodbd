<?php

namespace App\Http\Controllers\Admin\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Product\Product;
use App\Models\Report\DailyReport;
use App\Http\Controllers\Controller;
use App\Services\DailyReportService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyStockReportExport;

class DailyReportController extends Controller
{

   public function dailyReport(Request $request)
    {
        $search_date = $request->search_date ?? now()->format('Y-m-d');


        $dailyServiceReport = new DailyReportService(search_date: $search_date);
        $data = $dailyServiceReport->getReports();

       // return $data;

        $data['search_date'] = $search_date;

        if($request->ajax())
        {
            return response()->json([
                'status' => 'success',
                'start_date' => $dailyServiceReport->start_date,
                'end_date' => $search_date,
                'viewData' => view('admin.reports.includes.daily-report-table', $data)->render(),
            ], 200);
        }
        
        if ($request->has('export')) {
            try {
                return Excel::download(
                    new DailyStockReportExport($data),
                    'daily_stock_report_'.Carbon::now()->format('YmdHis').'.xlsx',
                    \Maatwebsite\Excel\Excel::XLSX
                );
            } catch (\Exception $e) {
                return back()->withError('Export failed: '.$e->getMessage());
            }
        }

        return view('admin.reports.daily-report', $data);
    }

    public function index()
    {
        $data['DailyReports'] = DailyReport::active()->get();

        return view('admin.DailyReport.view',$data);
    }

    public function create()
    {
        return view('admin.DailyReport.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_balance' => 'required',
        ]);

        $lastamount = 0;

        $latestBalance = DailyReport::where('date', DailyReport::max('date'))->first();

        if ($latestBalance) {
            $lastamount = $latestBalance->account_balance;
        }
        else{
           $lastamount = 0;
        }

        //DailyReport::where('date',$request->date)->delete();

        if(DailyReport::where('date',$request->date)->count()>0)
        {
             $notify[] = ['error', 'Day Already Closed'];
            return back()->withNotify($notify);
        }


        DailyReport::create(array_merge($request->all(), [
            'opening_balance'       => $lastamount ,
            'account_balance'       => $request->account_balance,
            'entry_id'              => auth('admin')->user()->id,
            'status'                => 'Active'
        ]));


        $notify[] = ['success', 'Day Closed'];
        return to_route('admin.reports.dailyreports')->withNotify($notify);
    }

    public function show(DailyReport $dailyReport)
    {
         return view('admin.DailyReport.show',compact('dailyReport'));
    }

    public function edit(DailyReport $dailyReport)
    {
        return view('admin.DailyReport.edit',compact('dailyReport'));
    }

    public function update(Request $request, DailyReport $dailyReport)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $dailyReport->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'DailyReport successfully Updated'];
        return to_route('admin.DailyReport.index')->withNotify($notify);
    }

    public function destroy(DailyReport $dailyReport)
    {
        $dailyReport->delete();
        $notify[] = ['success', "DailyReport deleted successfully"];
        return back()->withNotify($notify);
    }
}
