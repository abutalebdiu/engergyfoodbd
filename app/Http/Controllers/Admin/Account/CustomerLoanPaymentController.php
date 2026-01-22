<?php

namespace App\Http\Controllers\Admin\Account;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;
use App\Models\Account\CustomerLoanPayment;
use PDF;

class CustomerLoanPaymentController extends Controller
{

      public function index(Request $request)
      {
            //   Gate::authorize('admin.customerloanpayment.list');

            $query = CustomerLoanPayment::query();

            if ($request->start_date && $request->end_date) {
                  $data['start_date'] = $request->start_date;
                  $data['end_date'] = $request->end_date;
                  $query->whereBetween('date', [$request->start_date, $request->end_date]);
            } else {
                  $query->where('created_at', '>=', Carbon::now()->isCurrentMonth());
            }

            $data['customerloanpayments'] = $query->latest()->paginate(100);


            if ($request->has('search')) {
                  return view('admin.accounts.customerloanpayment.view', $data);
            } elseif ($request->has('pdf')) {
                  $pdf =  PDF::loadView('admin.accounts.customerloanpayment.view_pdf', $data);
                  return $pdf->stream('order_list.pdf');
            } elseif ($request->has('excel')) {
            } else {
                  return view('admin.accounts.customerloanpayment.view', $data);
            }
      }

      public function create()
      {
            // Gate::authorize('admin.customerloanpayment.create');

            return view('admin.accounts.customerloanpayment.create');
      }
      public function store(Request $request)
      {
            // Gate::authorize('admin.officialloanpayment.store');

            $request->validate([
                  'amount'            => 'required',
                  'payment_method_id' => 'required',
                  'account_id'        => 'required',
            ]);

            $customerloanpayment = CustomerLoanPayment::create(array_merge($request->all(), [
                  'entry_id'  => auth('admin')->user()->id,
                  'status'    => 'Active'
            ]));

            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no     = '';
            $transactionhistory->reference_no   = '';
            $transactionhistory->date           = $request->date ? $request->date : Date('Y-m-d');
            $transactionhistory->module_id      = 20; // Official Loan Payment
            $transactionhistory->module_invoice_id = $customerloanpayment->id;
            $transactionhistory->amount         = $request->amount;
            $transactionhistory->cdf_type       = 'debit';
            $transactionhistory->payment_method_id = $request->payment_method_id;
            $transactionhistory->account_id     = $request->account_id;
            $transactionhistory->note           = $request->note;
            $transactionhistory->save();

            $account = Account::find($request->account_id);
            $accountbalance = $account->balance($account->id) + ($request->amount);

            // transaction hoar ager balance
            $transactionhistory->pre_balance = $accountbalance;
            $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
            $transactionhistory->save();

            // Account Balance Update
            $account->main_balance = $accountbalance - ($request->amount);
            $account->save();


            // transaction hoar porer balance
            $transactionhistory->per_balance = $accountbalance - ($request->amount);
            $transactionhistory->save();



            $notify[] = ['success', 'Customer Loan Payment successfully Added'];
            return redirect()->back()->withNotify($notify);
      }
      public function show(CustomerLoanPayment $customerloanpayment)
      {
            Gate::authorize('admin.customerloanpayment.show');

            return view('admin.accounts.customerloanpayment.show', compact('customerloanpayment'));
      }

      public function edit(CustomerLoanPayment $customerloanpayment)
      {
            Gate::authorize('admin.customerloanpayment.edit');
            return view('admin.accounts.customerloanpayment.edit', compact('customerloanpayment'));
      }

      public function update(Request $request, CustomerLoanPayment $customerLoanPayment)
      {
            Gate::authorize('admin.customerloanpayment.update');
            $request->validate([
                  'name' => 'required',
            ]);

            $customerLoanPayment->update(array_merge($request->all(), [
                  'edit_id'  => auth('admin')->user()->id,
                  'edit_at'  => now(),
            ]));

            $notify[] = ['success', 'CustomerLoanPayment successfully Updated'];
            return to_route('admin.customerloanpayment.index')->withNotify($notify);
      }

      public function destroy(CustomerLoanPayment $customerloanpayment)
      {
            Gate::authorize('admin.customerloanpayment.destroy');
            $customerloanpayment->delete();
            $notify[] = ['success', "Customer Loan Payment deleted successfully"];
            return back()->withNotify($notify);
      }
}
