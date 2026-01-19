<?php


namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account\PaymentMethod;
use Illuminate\Support\Facades\Gate;

class PaymentMethodController extends Controller
{

    public function index()
    {
        Gate::authorize('admin.paymentmethod.list');

        $data['paymentmethods'] = PaymentMethod::active()->get();

        return view('admin.accounts.paymentmethods.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.paymentmethod.create');
        return view('admin.accounts.paymentmethods.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.paymentmethod.store');
        $request->validate([
            'name' => 'required',
        ]);

        PaymentMethod::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'PaymentMethod successfully Added'];
        return to_route('admin.paymentmethod.index')->withNotify($notify);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        Gate::authorize('admin.paymentmethod.show');
        return view('admin.PaymentMethod.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentmethod)
    {
        Gate::authorize('admin.paymentmethod.edit');
        return view('admin.accounts.paymentmethods.edit', compact('paymentmethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        Gate::authorize('admin.paymentmethod.update');
        $request->validate([
            'name' => 'required',
        ]);

        $paymentMethod->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Payment Method successfully Updated'];
        return to_route('admin.paymentmethod.index')->withNotify($notify);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        Gate::authorize('admin.paymentmethod.destroy');
        $paymentMethod->delete();
        $notify[] = ['success', "Payment Method deleted successfully"];
        return back()->withNotify($notify);
    }
}
