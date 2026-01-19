<?php

namespace App\Http\Controllers\Admin\HR;

use PDF;
use Carbon\Carbon;
use App\Models\HR\Loan;
use App\Models\HR\Employee;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Setting\Month;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;

class LoanController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.loan.list');

        $query = Loan::query();

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        else{
            $query->where('created_at', '>=', Carbon::now()->subHours(200));
        }

        $data['loans'] =  $query->get();

        if ($request->has('search')) {
            return view('admin.hr.loans.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.hr.loans.employee_loan_pdf', $data);
            return $pdf->stream('employee_loan.pdf');
        } elseif ($request->has('excel')) {
            // return Excel::download(new OrderExport($data), 'Order_list.xlsx');
        } else {
            return view('admin.hr.loans.view', $data);
        }

    }

    public function create()
    {
        Gate::authorize('admin.loan.create');
        $data['months']         = Month::get();
        $data['departments']    = Department::orderBy('position', 'ASC')->get();
        $data['years']          = Year::orderBy('name', 'DESC')->get();
        return view('admin.hr.loans.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.loan.store');
        $request->validate([
            'account_id'    => 'required',
            'employee_id'   => 'required',
            'amount'        => 'required',
            'total_amount'  => 'required',
        ]);

        $loan =   Loan::create(array_merge($request->except('department_id'), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Approved'
        ]));

        $loan->invoice_no = "LNP0" . $loan->id;
        $loan->save();

        if($request->type == "Regular")
        {
            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no = $loan->invoice_no;
            $transactionhistory->reference_no = '';
            $transactionhistory->module_id  = 13; // Loan Payment
            $transactionhistory->module_invoice_id = $loan->id;
            $transactionhistory->amount     = $request->amount;
            $transactionhistory->cdf_type   = 'debit';
            $transactionhistory->payment_method_id = $request->payment_method_id;
            $transactionhistory->account_id = $request->account_id;
            $transactionhistory->client_id  = $request->employee_id;
            $transactionhistory->note       = $request->note;
            $transactionhistory->save();

            $account = Account::find($request->account_id);

            // transaction hoar ager balance
            $transactionhistory->pre_balance = $account->main_balance;
            $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
            $transactionhistory->save();


            $account->main_balance = $account->main_balance - $request->amount;
            $account->save();

            // transaction hoar porer balance
            $transactionhistory->per_balance = $account->main_balance;
            $transactionhistory->save();


            // loan Calculation
            $employee = Employee::find($request->employee_id);
            $employee->loan_amount          = $request->amount;
            $employee->loan_paid            = 0;
            $employee->loan_due             = $request->amount;
            $employee->loan_installment     = $request->monthly_settlement;
            $employee->save();
        }
        else{
             // loan Calculation
             $employee = Employee::find($request->employee_id);
             $employee->loan_amount  = $request->amount;
             $employee->loan_paid    = 0;
             $employee->loan_due     = $request->amount;
             $employee->save();
        }

        $notify[] = ['success', "Loan successfully Added"];
        return to_route('admin.loan.index')->withNotify($notify);
    }

    public function show(Loan $loan)
    {
        Gate::authorize('admin.loan.show');
        return view('admin.hr.loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        Gate::authorize('admin.loan.edit');
        $data['months'] = Month::get();
        $data['employees'] = Employee::get();
        $data['years'] = Year::orderBy('name', 'DESC')->get();
        return view('admin.hr.loans.edit', compact('loan'), $data);
    }

    public function update(Request $request, Loan $loan)
    {
        Gate::authorize('admin.loan.update');
        $request->validate([
            'account_id'    => 'required',
            'employee_id'   => 'required',
            'amount'        => 'required',
            'total_amount'  => 'required',
        ]);

        $loan->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', "Loan successfully Updated"];
        return to_route('admin.loan.index')->withNotify($notify);
    }

    public function destroy(Loan $loan)
    {
        Gate::authorize('admin.loan.destroy');
        $loan->delete();
        $notify[] = ['success', "Loan deleted successfully"];
        return back()->withNotify($notify);
    }
}
