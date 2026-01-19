<?php

namespace App\Http\Controllers\Admin\Production;

use PDF;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\MakeProduction;
use App\Models\DailyProduction;
use App\Models\Product\Product;
use App\Exports\ProductionReport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductionReportDepartment;
use Carbon\Carbon;

class DailyProductionController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.dailyproduction.list');
        
        $query = DailyProduction::query();
        
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('date', '>=', Carbon::now()->subWeek());
        }

        $data['dailyproductions'] = $query->select(DB::raw('DATE(date) as date'), DB::raw('SUM(qty) as total_qty'))
                                            ->groupBy(DB::raw('DATE(date)'))
                                            ->orderBy('date', 'desc')
                                            ->paginate(100);

        return view('admin.productions.dailyproductions.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.dailyproduction.create');

        $data['productswithgroupes'] = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        return view('admin.productions.dailyproductions.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.dailyproduction.store');

        $input = $request->all();

        // Validate the input
        $request->validate([
            'date'              => 'required',
            'product_id'        => 'required|array',
            'product_id.*'      => 'exists:products,id',
            'product_qty'       => 'required|array',
            'product_qty.*'     => 'min:0'
        ]);

        $filteredProductIds     = [];
        $filteredProductQtys    = [];

        foreach (bn2en($input['product_qty']) as $index => $qty) {
            if ($qty > 0) {
                $filteredProductIds[] = $input['product_id'][$index];
                $filteredProductQtys[] = $qty;
            }
        }

        $input['product_id']    = $filteredProductIds;
        $input['product_qty']   = $filteredProductQtys;

        if (count($filteredProductQtys) > 0) {
            DB::beginTransaction();
            try {

                if ($input['product_id'] && $input['product_qty']) {
                    foreach ($input['product_id'] as $key => $value) {

                        $product = Product::find($value);

                        $dailyproduction                = new DailyProduction();
                        $dailyproduction->date          = $request->date;
                        $dailyproduction->product_id    = $value;
                        $dailyproduction->qty           = bn2en($input['product_qty'][$key]);
                        $dailyproduction->entry_id      = auth('admin')->user()->id;
                        $dailyproduction->save();


                        if ($product->pp_item_id) {
                            $dailyproduction->pp_item_id    = $product->pp_item_id;
                            $dailyproduction->pp_cost       = round(($product->ppitem->price/1000) * ((bn2en($input['product_qty'][$key]) * $product->pp_weight)),2);
                            $dailyproduction->save();
                            
                            $pp = Item::findOrFail($product->pp_item_id);
                            $pp->qty = $pp->stock($product->pp_item_id);
                            $pp->save();
                        }

                        if ($product->box_item_id) {
                            $dailyproduction->box_item_id = $product->box_item_id;
                            $dailyproduction->box_cost   = round($product->boxitem->price * bn2en($input['product_qty'][$key]), 2);
                            $dailyproduction->save();
                            
                            $box = Item::findOrFail($product->box_item_id);
                            $box->qty = $box->stock($product->box_item_id);
                            $box->save();
                        }

                        if ($product->striker_item_id) {
                            $dailyproduction->striker_item_id = $product->striker_item_id;
                            $dailyproduction->striker_cost   = round($product->strikeritem->price * bn2en($input['product_qty'][$key]), 2);
                            $dailyproduction->save();
                            
                            $striket = Item::findOrFail($product->striker_item_id);
                            $striket->qty = $striket->stock($product->striker_item_id);
                            $striket->save();
                        }

                        $product = Product::find($value);
                        $product->qty = $product->getstock($value);
                        $product->save();
                    }
                }

                DB::commit();

                $notify[] = ['success', 'Daily Production successfully Added'];
                return to_route('admin.dailyproduction.index')->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select products"];
            return back()->withNotify($notify);
        }
    }

    public function show(Request $request, $date)
    {
        Gate::authorize('admin.dailyproduction.show');

        $data['dailyproductions'] = DailyProduction::where('date', $date)
            ->with('product')
            ->get()
            ->groupBy(fn($item) => optional($item->product)->department_id);
            
        $data['date'] = $date;
        
        if($request->type=="pdf")
        {
            $pdf = PDF::loadView('admin.productions.dailyproductions.pdf', $data);
            return $pdf->stream('daily_production.pdf');
        }
        return view('admin.productions.dailyproductions.show', $data);
    }





    public function edit($date)
    {
        Gate::authorize('admin.dailyproduction.edit');

        $data['productswithgroupes']    = Product::where('status', 'Active')->with('department')->get()->groupby('department_id');
        $data['date']                   = $date;
        return view('admin.productions.dailyproductions.edit', $data);
    }

    public function update(Request $request, $date)
    {
        Gate::authorize('admin.dailyproduction.update');

        DailyProduction::where('date', $date)->delete();

        $input = $request->all();

        // Validate the input
        $request->validate([
            'date'              => 'required',
            'product_id'        => 'required|array',
            'product_id.*'      => 'exists:products,id',
            'product_qty'       => 'required|array',
            'product_qty.*'     => 'min:0'
        ]);

        $filteredProductIds     = [];
        $filteredProductQtys    = [];

        foreach (bn2en($input['product_qty']) as $index => $qty) {
            if ($qty > 0) {
                $filteredProductIds[] = $input['product_id'][$index];
                $filteredProductQtys[] = $qty;
            }
        }

        $input['product_id']    = $filteredProductIds;
        $input['product_qty']   = $filteredProductQtys;

        if (count($filteredProductQtys) > 0) {
            DB::beginTransaction();
            try {

                if ($input['product_id'] && $input['product_qty']) {
                    foreach ($input['product_id'] as $key => $value) {

                        $product = Product::find($value);

                        $dailyproduction                = new DailyProduction();
                        $dailyproduction->date          = $request->date;
                        $dailyproduction->product_id    = $value;
                        $dailyproduction->qty           = $input['product_qty'][$key];
                        $dailyproduction->entry_id      = auth('admin')->user()->id;
                        $dailyproduction->save();

                        if ($product->pp_item_id) {
                            $dailyproduction->pp_item_id    = $product->pp_item_id;
                            $dailyproduction->pp_cost       = round(($product->ppitem->price/1000) * ((bn2en($input['product_qty'][$key]) * $product->pp_weight)),2);
                            $dailyproduction->save();
                            
                            $pp = Item::findOrFail($product->pp_item_id);
                            $pp->qty = $pp->stock($product->pp_item_id);
                            $pp->save();
                        }
                        if ($product->box_item_id) {
                            $dailyproduction->box_item_id   = $product->box_item_id;
                            $dailyproduction->box_cost      = round($product->boxitem->price * bn2en($input['product_qty'][$key]), 2);
                            $dailyproduction->save();
                            
                            $box = Item::findOrFail($product->box_item_id);
                            $box->qty = $box->stock($product->box_item_id);
                            $box->save();
                        }

                        if ($product->striker_item_id) {
                            $dailyproduction->striker_item_id = $product->striker_item_id;
                            $dailyproduction->striker_cost   = round($product->strikeritem->price * bn2en($input['product_qty'][$key]), 2);
                            $dailyproduction->save();
                            
                            $striket = Item::findOrFail($product->striker_item_id);
                            $striket->qty = $striket->stock($product->striker_item_id);
                            $striket->save();
                        }

                        $product = Product::find($value);
                        $product->qty = $product->getstock($value);
                        $product->save();
                    }
                }

                DB::commit();

                $notify[] = ['success', 'Daily Production successfully Added'];
                return to_route('admin.dailyproduction.index')->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select products"];
            return back()->withNotify($notify);
        }
    }

    public function destroy($date)
    {
       // Gate::authorize('admin.dailyproduction.destroy');
       
        DailyProduction::where('date',$date)->delete();
         
        $notify[] = ['success', "Daily Production Deleted successfully"];
        return back()->withNotify($notify);
    }
    
    
    public function entryreport(Request $request)
    {
        $data['searching'] = "Yes";
        $data['products'] = Product::where('status', 'Active')->get();
        $data['dates'] = [];
        $productions = collect();
        $dateWiseSum = [];
    
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
    
            // Generate date range
            $dates = \Carbon\CarbonPeriod::create($request->start_date, $request->end_date);
            $data['dates'] = collect($dates)->map(fn($date) => $date->format('Y-m-d'))->toArray();
    
            // Initialize all dates with 0 to prevent undefined key error
            foreach ($data['dates'] as $date) {
                $dateWiseSum[$date] = 0;
            }
    
            // Fetch production data and calculate sums
            $productions = DailyProduction::whereBetween('date', [$request->start_date, $request->end_date])
                ->select('product_id', 'date', \DB::raw('SUM(qty) as total_qty'))
                ->groupBy('product_id', 'date')
                ->get()
                ->groupBy('product_id');
    
            // Fetch actual date-wise sums and update initialized array
            $actualSums = DailyProduction::whereBetween('date', [$request->start_date, $request->end_date])
                ->select('date', \DB::raw('SUM(qty) as total_qty'))
                ->groupBy('date')
                ->pluck('total_qty', 'date')
                ->toArray();
    
            foreach ($actualSums as $date => $sum) {
                $dateWiseSum[$date] = $sum;
            }
        } else {
            $data['start_date'] = '';
            $data['end_date'] = '';
        }
    
        $data['productions'] = $productions;
        $data['dateWiseSum'] = $dateWiseSum;
    
        if($request->has('pdf')){
            $pdf = PDF::loadView('admin.productions.dailyproductions.entryreport_pdf', $data,[],[
                'format'        => 'A4', // or 'A3', 'Letter', etc.
                'orientation'   => 'Landscape' // 'P' for Portrait, 'L' for Landscape
            ]);
            return $pdf->stream('daily_production_entry_report.pdf');
        }
        elseif($request->has('search'))
        {
            return view('admin.productions.dailyproductions.entryreport', $data);
        }
        else{
            return view('admin.productions.dailyproductions.entryreport', $data);
        }
    
       
    }



    // public function productionReport(Request $request)
    // {
    //     $startDate = $request->start_date ?? date('Y-m-d');
    //     $endDate = $request->end_date ?? date('Y-m-d');
    //     $departmentId = $request->department_id;

    //     $data = [
    //         'departments'   => Department::where('is_p','Yes')->get(),
    //         'start_date'    => $startDate,
    //         'end_date'      => $endDate,
    //         'department_id' => $departmentId,
    //         'searching'     => $departmentId ? 'Yes' : 'No',
    //     ];

    //     if ($departmentId) {
    //         $data['products'] = Product::with(['dailyProductions', 'recipes.item'])
    //             ->where('department_id', $departmentId)
    //             ->get();

    //         $data['items'] = Item::whereHas('recipes.product', function ($query) use ($departmentId) {
    //             $query->where('department_id', $departmentId);
    //         })
    //             ->select('items.id', 'items.name', 'items.price')
    //             ->distinct()
    //             ->get();
    //     }

    //     if ($request->export == 'export') {
    //         return Excel::download(new ProductionReport($data), 'production_report.xlsx');
    //     }


    //     return view('admin.productions.dailyproductions.dailyreport', $data);
    // }


    public function productionreport(Request $request)
    {
        Gate::authorize('admin.dailyproduction.productionreport');
        $data = [
            'departments' => Department::all(),
            'start_date' => $request->start_date ?? date('Y-m-d'),
            'end_date' => $request->end_date ?? date('Y-m-d'),
            'department_id' => $request->department_id,
            'searching' => $request->department_id ? 'Yes' : 'No',
        ];

        if ($data['department_id']) {
            $data['products'] = Product::select('id', 'name', 'sale_price', 'yeast')
                ->where('department_id', $data['department_id'])
                ->orderBy('id')
                ->get();

            // Fetch only items associated with these products
            $productIds = $data['products']->pluck('id');
            $data['items'] = Item::select('items.id', 'items.name', 'items.price')
                ->join('product_recipes', 'items.id', '=', 'product_recipes.item_id')
                ->whereIn('product_recipes.product_id', $productIds)
                ->groupBy('items.id', 'items.name', 'items.price')
                ->get();


            $depermentProfitLoss = $this->getProductionProfitLoss($data['department_id'], $data['start_date'], $data['end_date']);

            $data['total_received_qty'] = $depermentProfitLoss['total_received_qty'];
            $data['total_received_cost'] = $depermentProfitLoss['total_received_cost'];
            $data['total_pp_cost'] = $depermentProfitLoss['total_pp_cost'];
            $data['total_box_cost'] = $depermentProfitLoss['total_box_cost'];
            $data['total_striker_cost'] = $depermentProfitLoss['total_striker_cost'];
            $data['total_cost'] = $depermentProfitLoss['total_cost'];
            $data['production_qty'] = $depermentProfitLoss['production_qty'];
            $data['production_price'] = $depermentProfitLoss['production_price'];
            $data['profit_or_loss'] = $depermentProfitLoss['profit_or_loss'];
            $data['profit_or_loss_percentage'] = $depermentProfitLoss['profit_or_loss_percentage'];


            // return $data;


            if ($request->export == 'export') {
                return Excel::download(new ProductionReport(
                    $data['products'],
                    $data['items'],
                    $data['start_date'],
                    $data['end_date'],
                    $data['department_id'],
                    $data['total_received_qty'],
                    $data['total_received_cost'],
                    $data['total_pp_cost'],
                    $data['total_box_cost'],
                    $data['total_striker_cost'],
                    $data['total_cost'],
                    $data['production_qty'],
                    $data['production_price'],
                    $data['profit_or_loss'],
                    $data['profit_or_loss_percentage']
                ), 'daily_production_report.xlsx');
            } elseif ($request->export == 'pdf') {
                $pdf = PDF::loadView('admin.productions.dailyproductions.daily_production_report_excel', $data);

                return $pdf->stream('daily_production_report.pdf');
            }
        }


        return view('admin.productions.dailyproductions.dailyreport', $data);
    }

    protected function getProductionProfitLoss($department_id, $startDate, $endDate)
    {
        $department = Department::find($department_id);

        $departmentItems = [
            'id' => $department->id,
            'name' => $department->name,
            'total_received_qty' => 0,
            'total_received_cost' => 0,
            'total_pp_cost' => 0,
            'total_box_cost' => 0,
            'total_striker_cost' => 0,
            'total_cost' => 0,
            'production_qty' => 0,
            'production_price' => 0,
            'profit_or_loss' => 0,
            'profit_or_loss_percentage' => 0,
        ];

        if ($department) {
            $makeProductions = MakeProduction::with('item')
                ->where('department_id', $department->id)

                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            foreach ($makeProductions as $makeProduction) {
                $departmentItems['total_received_qty'] += $makeProduction->qty;
                $departmentItems['total_received_cost'] += $makeProduction->item->price * $makeProduction->qty;
            }


            foreach ($department->products as $product) {
                $filteredProductions = $product->dailyProductions->whereBetween('date', [$startDate, $endDate]);

                foreach ($filteredProductions as $dailyProduction) {
                    $departmentItems['production_qty'] += $dailyProduction->qty;
                    $departmentItems['production_price'] += $product->sale_price * $dailyProduction->qty;
                    $departmentItems['total_pp_cost'] += $dailyProduction->pp_cost;
                    $departmentItems['total_box_cost'] += $dailyProduction->box_cost;
                    $departmentItems['total_striker_cost'] += $dailyProduction->striker_cost;
                }
            }
            $departmentItems['total_cost'] = $departmentItems['total_received_cost'] + $departmentItems['total_pp_cost'] + $departmentItems['total_box_cost'] + $departmentItems['total_striker_cost'];
            $departmentItems['profit_or_loss'] = $departmentItems['production_price'] - $departmentItems['total_cost'];
            $departmentItems['profit_or_loss_percentage'] = $departmentItems['total_cost'] > 0
                ? ($departmentItems['profit_or_loss'] / $departmentItems['total_cost']) * 100
                : 0;
        }

        return $departmentItems;
    }

    public function productionGroupReport(Request $request)
    {
        Gate::authorize('admin.dailyproduction.productionGroupReport');

        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');
        $departmentId = $request->department_id;

        $departmentss = Department::where('is_p', 'Yes')->get();
        
        $departmentsQuery = Department::with(['products.dailyProductions', 'products.recipes.item'])->where('is_p', 'Yes');

        if ($departmentId) {
            $departmentsQuery->where('id', $departmentId);
        }

        $departments = $departmentsQuery->get();

        $items = [];

        foreach ($departments as $department) {
            $departmentItems = [
                'id' => $department->id,
                'name' => $department->name,
                'total_received_qty' => 0,
                'total_received_cost' => 0,
                'total_pp_cost' => 0,
                'total_box_cost' => 0,
                'total_striker_cost' => 0,
                'total_cost' => 0,
                'production_qty' => 0,
                'production_gram' => 0,
                'production_price' => 0,
                'profit_or_loss' => 0,
                'profit_or_loss_percentage' => 0,
            ];



            $makeProductions = MakeProduction::with('item')
                ->where('department_id', $department->id)

                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            foreach ($makeProductions as $makeProduction) {
                $makeqty =   (($makeProduction->item->weight_gram * $makeProduction->qty)/1000);
                $departmentItems['total_received_qty']  += $makeqty;
                $departmentItems['total_received_cost'] += $makeProduction->item->price * $makeqty;
            }


            foreach ($department->products as $product) {
                $filteredProductions = $product->dailyProductions->whereBetween('date', [$startDate, $endDate]);

                foreach ($filteredProductions as $dailyProduction) {
                    $departmentItems['production_qty'] += $dailyProduction->qty;
                    $departmentItems['production_gram'] += $dailyProduction->qty * $product->weight_gram;
                    $departmentItems['production_price'] += $product->sale_price * $dailyProduction->qty;
                    $departmentItems['total_pp_cost'] += $dailyProduction->pp_cost;
                    $departmentItems['total_box_cost'] += $dailyProduction->box_cost;
                    $departmentItems['total_striker_cost'] += $dailyProduction->striker_cost;
                }
            }

            $departmentItems['total_cost'] = $departmentItems['total_received_cost'] + $departmentItems['total_pp_cost'] + $departmentItems['total_box_cost'] + $departmentItems['total_striker_cost'];
            $departmentItems['profit_or_loss'] = $departmentItems['production_price'] - $departmentItems['total_cost'];
            $departmentItems['profit_or_loss_percentage'] = $departmentItems['total_cost'] > 0
                ? ($departmentItems['profit_or_loss'] / $departmentItems['total_cost']) * 100
                : 0;

            $items[] = $departmentItems;
        }

        $data = [
            'departments' => $departmentss,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'department_id' => $departmentId,
            'items' => $items,
        ];

        //return $data;


        if ($request->export == 'export') {
            return Excel::download(new ProductionReportDepartment($data), 'department_production_report.xlsx');
        } elseif ($request->export == 'pdf') {
            $pdf = PDF::loadView('admin.productions.dailyproductions.group_report_pdf', [
                'items' => $data['items'],
            ]);

            return $pdf->stream('department_production_report.pdf');
        }

        return view('admin.productions.dailyproductions.group_report', $data);
    }
}
