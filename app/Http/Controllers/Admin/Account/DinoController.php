<?php

namespace App\Http\Controllers\Admin\Account;
use App\Http\Controllers\Controller;
use App\Models\Dino;
use Illuminate\Http\Request;


class DinoController extends Controller
{

    public function index()
    {
        $data['Dinos'] = Dino::active()->get();

        return view('admin.Dino.view',$data);
    }

    public function create()
    {
        return view('admin.Dino.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Dino::create(array_merge($request->all(), [
            'entry_id'  => auth('admin')->user()->id,
            'status'    => 'Active'
        ]));

        $notify[] = ['success', 'Dino successfully Added'];
        return to_route('admin.Dino.index')->withNotify($notify);
    }

    public function show(Dino $dino)
    {
         return view('admin.Dino.show',compact('dino'));
    }

    public function edit(Dino $dino)
    {
        return view('admin.Dino.edit',compact('dino'));
    }

    public function update(Request $request, Dino $dino)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $dino->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Dino successfully Updated'];
        return to_route('admin.Dino.index')->withNotify($notify);
    }

    public function destroy(Dino $dino)
    {
        $dino->delete();
        $notify[] = ['success', "Dino deleted successfully"];
        return back()->withNotify($notify);
    }
}
