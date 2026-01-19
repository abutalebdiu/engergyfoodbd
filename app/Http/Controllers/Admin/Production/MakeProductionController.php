<?php

namespace App\Http\Controllers\Admin\Production;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\MakeProduction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use PDF;

class MakeProductionController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.makeproduction.list');
        
         $query = MakeProduction::query();
        
        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('date', '>=', Carbon::now()->subWeek());
        }

        $data['productions'] = $query->select('date', 'department_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('date', 'department_id')
            ->orderBy('date', 'desc')
            ->get();
        return view('admin.productions.makeproductions.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.makeproduction.create');

        $data['itemsgroupes'] = Item::orderby('name', 'asc')->with('category')->get()->groupby('item_category_id');
        $data['departments']  = Department::where('is_p', 'Yes')->get();
        return view('admin.productions.makeproductions.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.makeproduction.store');

        $request->validate([
            'department_id' => 'required',
            'date'          => 'required',
            'item_id'       => 'required|array',
            'item_id.*'     => 'exists:items,id',
            'item_qty'      => 'required|array'
        ]);

        $input = $request->all();

        $filteredItemIds = [];
        $filteredItemQtys = [];

        foreach (bn2en($input['item_qty']) as $index => $qty) {
            if ($qty > 0) {
                $filteredItemIds[] = $input['item_id'][$index];
                $filteredItemQtys[] = $qty;
            }
        }

        $input['item_id'] = $filteredItemIds;
        $input['item_qty'] = $filteredItemQtys;

        if (count($filteredItemQtys) > 0) {
            DB::beginTransaction();
            try {
                if ($input['item_id'] && $input['item_qty']) {
                    foreach ($input['item_id'] as $key => $value) {
                        $makeproduction = new MakeProduction();
                        $makeproduction->date           = $request->date;
                        $makeproduction->department_id  = $request->department_id;
                        $makeproduction->item_id        = $value;
                        $makeproduction->qty            = $input['item_qty'][$key];
                        $makeproduction->save();
                    }
                }

                DB::commit();

                $notify[] = ['success', 'successfully Added'];
                return to_route('admin.makeproduction.index')->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select Items"];
            return back()->withNotify($notify);
        }
    }

    public function show(Request $request, $date)
    {
        Gate::authorize('admin.makeproduction.show');

        $data['department']         = Department::FindOrFail($request->department_id);
        $data['makeproductions']    = MakeProduction::where('date', $date)
                                                    ->where('department_id', $request->department_id)
                                                    ->get();
        $data['date'] = $date;
        
        if($request->type=="pdf")
        {
            $pdf = PDF::loadView('admin.productions.makeproductions.pdf', $data);
            return $pdf->stream('make_production.pdf');
        }
            
        return view('admin.productions.makeproductions.show', $data);
    }

    public function edit(Request $request, $date)
    {
        Gate::authorize('admin.makeproduction.edit');

        $data['date']           = $date;
        $data['department_id']  = $request->department_id;
        $data['itemsgroupes']   = Item::orderby('name', 'asc')->with('category')->get()->groupby('item_category_id');
        $data['departments']    = Department::where('is_p', 'Yes')->get();

        return view('admin.productions.makeproductions.edit', $data);
    }

    public function update(Request $request, $date)
    {
        Gate::authorize('admin.makeproduction.update');

        MakeProduction::where('date', $date)->where('department_id', $request->department_id)->delete();

        $request->validate([
            'department_id' => 'required',
            'date'          => 'required',
            'item_id'       => 'required|array',
            'item_id.*'     => 'exists:items,id',
            'item_qty'      => 'required|array'
        ]);

        $input = $request->all();

        $filteredItemIds = [];
        $filteredItemQtys = [];

        foreach (bn2en($input['item_qty']) as $index => $qty) {
            if ($qty > 0) {
                $filteredItemIds[] = $input['item_id'][$index];
                $filteredItemQtys[] = $qty;
            }
        }

        $input['item_id'] = $filteredItemIds;
        $input['item_qty'] = $filteredItemQtys;

        if (count($filteredItemQtys) > 0) {
            DB::beginTransaction();
            try {
                if ($input['item_id'] && $input['item_qty']) {
                    foreach ($input['item_id'] as $key => $value) {
                        $makeproduction = new MakeProduction();
                        $makeproduction->date           = $request->date;
                        $makeproduction->department_id  = $request->department_id;
                        $makeproduction->item_id        = $value;
                        $makeproduction->qty            = $input['item_qty'][$key];
                        $makeproduction->save();
                    }
                }

                DB::commit();

                $notify[] = ['success', 'successfully Updated'];
                return to_route('admin.makeproduction.index')->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', "An error occurred while processing your request." . $e->getMessage()];
                return back()->withNotify($notify);
            }
        } else {
            $notify[] = ['error', "Please select Items"];
            return back()->withNotify($notify);
        }
    }

    public function destroy(Request $request, $date)
    {
        Gate::authorize('admin.makeproduction.destroy');

        MakeProduction::where('date',$date)->where('department_id',$request->department_id)->delete();

        $notify[] = ['success', "Successfully Deleted"];
        return back()->withNotify($notify);
    }
}
