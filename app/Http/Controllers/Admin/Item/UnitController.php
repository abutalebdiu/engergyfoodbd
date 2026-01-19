<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('admin.unit.list');

        $data['units'] = Unit::all();

        return view('admin.items.units.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        Gate::authorize('admin.unit.create');
        if (isset($id)) {
            $data['title'] = 'Edit Unit';
            $data['unit'] = Unit::find($id);
        } else {
            $data['title'] = 'Add Unit';
        }

        return view('admin.items.units.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null)
    {
        Gate::authorize('admin.unit.store');
        if ($id == null) {
            $unit = new Unit();
        } else {
            $unit = Unit::find($id);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
            'base_unit' => 'required',
            'value'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $unit->name = $request->name;
        $unit->symbol = $request->symbol;
        $unit->base_unit = $request->base_unit;
        $unit->value = $request->value;
        $unit->save();

        return redirect()->route('admin.items.unit.index')->with('success', 'Unit added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('admin.unit.show');
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('admin.unit.edit');
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('admin.unit.update');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admin.unit.destroy');
        $unit = Unit::find($id);
        $unit->delete();

        return redirect()->route('admin.items.unit.index')->with('success', 'Unit deleted successfully');
    }
}
