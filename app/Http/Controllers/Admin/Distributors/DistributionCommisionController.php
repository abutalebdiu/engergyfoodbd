<?php

namespace App\Http\Controllers\Admin\Distributors;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Models\Distribution\Distribution;
use Illuminate\Support\Facades\Validator;
use PDF;

class DistributionCommisionController extends Controller
{
    public function index(Request $request, $id)
    {
        $data['distributor'] = Distribution::where('status', 1)->where('id', $id)->first();

        $data['products'] = Product::active()
            ->with([
                'department',
                'distributorCommission' => function ($query) use ($id) {
                    $query->where('distribution_id', $id);
                }
            ])
            ->orderBy('department_id')
            ->get();

        return view('admin.distributors.distributioncommision.index', $data);
    }

    public function referenceCommisionUpdate(Request $request, $id)
    {

        if (!$id) {
            $notify['warning'] = 'Something went wrong';
            return redirect()->back()->withNotify($notify);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'amount' => 'required|array',
            'amount.*' => 'required|min:0',
            'price' => 'required|array',
            'price.*' => 'required|min:0',
            'type' => 'required|array',
            'type.*' => 'required|in:Percentage,Flat',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $distributor = Distribution::find($id);

        if (!$distributor) {

            $notify['warning'] = 'Something went wrong';
            return redirect()->back()->withNotify($notify);

        }

        foreach ($request->product_id as $key => $item) {

            $product = Product::find($item);

            if ($product) {
                $product->distributorCommission()->updateOrCreate(
                    [
                        'distribution_id' => $distributor->id,
                        'product_id' => $item,
                    ],
                    [
                        'price' => bn2en($request->price[$key]),
                        'amount' => bn2en($request->amount[$key]),
                        'type' => $request->type[$key],
                        'entry_id' => auth('admin')->user()->id
                    ]
                );
            }
        }

        $notify[] = ['success', 'Commission updated successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function referenceCommisionpdf($id)
    {
        $data['distributor'] = Distribution::findOrFail($id);

        $data['products'] = Product::active()
            ->with([
                'department',
                'distributorCommission' => function ($query) use ($id) {
                    $query->where('distribution_id', $id);
                }
            ])
            ->orderBy('department_id')
            ->get();


        $pdf = PDF::loadView('admin.distributors.distributioncommision.distributor_product_commission_pdf', $data);
        return $pdf->stream('distributor_product_commission.pdf');
    }
}



   