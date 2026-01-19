<?php

namespace App\Http\Controllers\Admin\HR;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Distribution\Distribution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistributionController extends Controller
{
      public function index(Request $request)
      {
            $distributions = Distribution::latest()->paginate(10);

            if ($request->has('search')) {
                  return view('admin.distribution.index', compact('distributions'));
            } elseif ($request->has('pdf')) {
                  $pdf =  Pdf::loadView('admin.distribution.index_pdf', compact('distributions'));
                  return $pdf->download('Distribution.pdf');
            } else {
                  return view('admin.hr.distributions.index', compact('distributions'));
            }
      }

      public function create()
      {
            return view('admin.hr.distributions.create');
      }

      public function store(Request $request)
      {
            $request->validate([
                  'name' => 'required|string|max:100',
                  'mobile' => 'required|string|max:20',
                  'email' => 'required|email|unique:distributions,email',
                  'address' => 'required|string|max:255',
                  'status' => 'required|in:Active,Inactive',
            ]);

            Distribution::create($request->all());
            return redirect()->route('admin.distribution.index')->with('success', 'Distribution added successfully.');
      }

      public function edit(Distribution $distribution)
      {
            return view('admin.hr.distributions.edit', compact('distribution'));
      }

      public function update(Request $request, Distribution $distribution)
      {
            $request->validate([
                  'name' => 'required|string|max:100',
                  'mobile' => 'required|string|max:20',
                  'email' => 'required|email|unique:distributions,email,' . $distribution->id,
                  'address' => 'required|string|max:255',
                  'status' => 'required|in:Active,Inactive',
            ]);

            $distribution->update($request->all());
            return redirect()->route('admin.distribution.index')->with('success', 'Distribution updated successfully.');
      }

      public function show(Distribution $distribution)
      {
            return view('admin.hr.distributions.show', compact('distribution'));
      }
      public function destroy(Distribution $distribution)
      {
            $distribution->delete();
            return redirect()->route('admin.distribution.index')->with('success', 'Distribution deleted successfully.');
      }
      public function status(Request $request, $id)
      {
            $distribution = Distribution::findOrFail($id);
            if ($distribution->status == 'Active') {
                  $distribution->status = 'Inactive';
                  $notify[] = ['success', 'Distribution successfully Inactive'];
            } else {
                  $distribution->status = 'Active';
                  $notify[] = ['success', 'Distribution successfully Active'];
            }
            $distribution->save();
            return back()->withNotify($notify);
      }


      
      public function statement(Distribution $distribution)
      {
            return view('admin.hr.distributions.statement', compact('distribution'));
      }
}
