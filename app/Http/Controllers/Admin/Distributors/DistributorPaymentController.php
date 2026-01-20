<?php

namespace App\Http\Controllers\Admin\Distributors;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Distribution\Distribution;
use App\Models\Distribution\DistributionOrderPayment;
use PDF; 

class DistributorPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DistributionOrderPayment::with(['distribution', 'paymentMethod', 'account']);

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        } elseif ($request->from_date) {
            $query->where('date', '>=', $request->from_date);
        } elseif ($request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }

        if ($request->distribution_id) {
            $query->where('distribution_id', $request->distribution_id);
        }

        $payments = $query->orderBy('date', 'desc')->paginate(20);
        $distributors = Distribution::orderBy('name')->get();

        return view('admin.distributors.distributor_payment.index', compact('payments', 'distributors'));
    }

    public function exportPdf(Request $request)
    {
        $query = DistributionOrderPayment::with(['distribution', 'paymentMethod', 'account']);

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        } elseif ($request->from_date) {
            $query->where('date', '>=', $request->from_date);
        } elseif ($request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }

        if ($request->distribution_id) {
            $query->where('distribution_id', $request->distribution_id);
        }

        $payments = $query->orderBy('date', 'desc')->get();

        $pdf = PDF::loadView('admin.distributors.distributor_payment.pdf', compact('payments'));
        return $pdf->stream('distributor_payments.pdf');
    }
}
