<?php

namespace App\Http\Controllers\Admin\Commission;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PDF;

class ReferenceCommisionController extends Controller
{
    public function referenceCommision($id)
    {
        $data['user'] = User::findOrFail($id);
    
        // Load all products with department relation and commission
        $data['products'] = Product::active()
            ->with([
                'department',
                'productCommission' => function ($query) use ($id) {
                    $query->where('user_id', $id);
                }
            ])
            ->orderBy('department_id')
            ->get();
    
        return view('admin.commissions.referencecommissions.index', $data);
    }
    
    
    
    public function referenceCommisionpdf($id)
    {
        $data['customer'] = User::findOrFail($id);
    
        // Load all products with department relation and commission
        $data['products'] = Product::active()
            ->with([
                'department',
                'productCommission' => function ($query) use ($id) {
                    $query->where('user_id', $id);
                }
            ])
            ->orderBy('department_id')
            ->get();
            
        
        //  return view('admin.commissions.referencecommissions.customer_commission_pdf', $data);
    
         $pdf = PDF::loadView('admin.commissions.referencecommissions.customer_commission_pdf',$data);
         return $pdf->stream('customer_product_commission.pdf');
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
        $user = User::find($id);

        if (!$user) {
            $notify['warning'] = 'Something went wrong';
            return redirect()->back()->withNotify($notify);
        }

        foreach ($request->product_id as $key => $item) {
            $product = Product::find($item);
            if ($product) {
                $product->productCommission()->updateOrCreate(
                    [
                        'user_id' => $user->id,
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

}
