<?php

namespace App\Http\Controllers\Admin\HR;

use PDF;
use Carbon\Carbon;
use App\Models\HR\Employee;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Setting\Month;
use App\Models\Account\Account;
use App\Models\HR\SalaryGenerate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\HR\SalaryPaymentHistory;
use App\Models\Account\TransactionHistory;

class SalaryPaymentHistoryController extends Controller
{

    public function index(Request $request)
    {

        Gate::authorize('admin.salarypaymenthistory.list');

        $data['employees'] = Employee::get(['id', 'emp_id', 'name']);

        $query = SalaryPaymentHistory::query();

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(40));
        }

        $data['histories'] = $query->get();

        if ($request->has('search')) {
            return view('admin.hr.salarypayments.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.hr.salarypayments.salary_payments_pdf', $data);
            return $pdf->stream('customer_due_payment.pdf');
        } elseif ($request->has('excel')) {
            // return Excel::download(new OrderExport($data), 'Order_list.xlsx');
        } else {
            return view('admin.hr.salarypayments.view', $data);
        }
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.salarypaymenthistory.create');
        $data['departments']    = Department::orderBy('position', 'ASC')->get();
        $data['months']         = Month::get();
        $data['years']          = Year::orderby('name','desc')->get();


        $query = SalaryGenerate::query()->with('employee');

        if ($request->month_id) {
            $data['month_id'] = $request->month_id;
            $query->where('month_id', $request->month_id);
        }
        if ($request->year_id) {
            $data['year_id'] = $request->year_id;
            $query->where('year_id', $request->year_id);
        }

        if ($request->department_id) {
            $data['department_id'] = $request->department_id;
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
            $data['searching'] = "Yes";
        } else {
            $query->whereHas('employee', function ($q) {
                $q->where('department_id', 0);
            });
            $data['searching'] = "No";
        }

        $data['salarygenerates'] = $query->where('status','Generated')->get();

        return view('admin.hr.salarypayments.create', $data);
    }

    // public function store(Request $request)
    // {

    //     Gate::authorize('admin.salarypaymenthistory.store');
    //     $request->validate([
    //         'account_id'            => 'required',
    //         'salary_generate_id'    => 'required',
    //         'amount'                => 'required',
    //     ]);

    //     foreach ($request->salary_generate_id as $key => $salary_generateid) {

    //         $history = new SalaryPaymentHistory();
    //         $history->date                  = $request->date ? $request->date : Date('Y-m-d');
    //         $history->employee_id           = $request->employee_id[$key];
    //         $history->salary_generate_id    = $request->salary_generate_id[$key];
    //         $history->amount                = bn2en($request->amount[$key]);
    //         $history->payment_method_id     = $request->payment_method_id;
    //         $history->account_id            = $request->account_id;
    //         $history->entry_id              = auth('admin')->user()->id;
    //         $history->save();

    //         $history->invoice_no = 'SP0' . $history->id;
    //         $history->save();


    //         $salarygenerate = SalaryGenerate::find($request->salary_generate_id[$key]);
    //         $salarygenerate->status = $salarygenerate->salarypayment->sum('amount') == $salarygenerate->payable_amount ? "Paid" : "Generated";
    //         $salarygenerate->save();


    //         $transactionhistory = new TransactionHistory();
    //         $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
    //         $transactionhistory->invoice_no = $history->invoice_no;
    //         $transactionhistory->reference_no = '';
    //         $transactionhistory->module_id  = 6; // Salary Expense Payment
    //         $transactionhistory->module_invoice_id = $history->id;
    //         $transactionhistory->amount     = bn2en($request->amount[$key]);
    //         $transactionhistory->cdf_type   = 'debit';
    //         $transactionhistory->payment_method_id = $request->payment_method_id;
    //         $transactionhistory->account_id = $request->account_id;
    //         $transactionhistory->client_id  = $history->employee_id;
    //         $transactionhistory->note       = $request->note;
    //         $transactionhistory->save();

    //         $account = Account::find($request->account_id);

    //         // transaction hoar ager balance
    //         $transactionhistory->pre_balance = $account->main_balance;
    //         $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
    //         $transactionhistory->save();


    //         $account->main_balance = $account->main_balance - bn2en($request->amount[$key]);
    //         $account->save();

    //         // transaction hoar porer balance
    //         $transactionhistory->per_balance = $account->main_balance;
    //         $transactionhistory->save();
    //     }


    //     $notify[] = ['success', 'Salary Payment Added'];
    //     return to_route('admin.salarypaymenthistory.index')->withNotify($notify);
    // }
    
    
    public function store(Request $request)
    {
    Gate::authorize('admin.salarypaymenthistory.store');

    $request->validate([
        'account_id'         => 'required',
        'salary_generate_id' => 'required|array',
        'amount'             => 'required|array',
        'sal_pay_id'         => 'required|array', // checkbox validation
    ]);

    foreach ($request->sal_pay_id as $key) {

        $history = new SalaryPaymentHistory();
        $history->date               = $request->date ?? date('Y-m-d');
        $history->employee_id        = $request->employee_id[$key];
        $history->salary_generate_id = $request->salary_generate_id[$key];
        $history->amount             = bn2en($request->amount[$key]);
        $history->payment_method_id  = $request->payment_method_id;
        $history->account_id         = $request->account_id;
        $history->entry_id           = auth('admin')->user()->id;
        $history->save();

        $history->invoice_no = 'SP0' . $history->id;
        $history->save();

        // Salary Generate Status Update
        $salarygenerate = SalaryGenerate::find($history->salary_generate_id);
        $salarygenerate->status =
            $salarygenerate->salarypayment->sum('amount') == $salarygenerate->payable_amount
            ? 'Paid'
            : 'Generated';
        $salarygenerate->save();

        // Transaction History
        $transactionhistory = new TransactionHistory();
        $transactionhistory->date = $history->date;
        $transactionhistory->invoice_no = $history->invoice_no;
        $transactionhistory->module_id = 6;
        $transactionhistory->module_invoice_id = $history->id;
        $transactionhistory->amount = $history->amount;
        $transactionhistory->cdf_type = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id = $history->employee_id;
        $transactionhistory->note = $request->note;
        $transactionhistory->save();

        $account = Account::find($request->account_id);

        $transactionhistory->pre_balance = $account->main_balance;
        $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
        $transactionhistory->save();

        $account->main_balance -= $history->amount;
        $account->save();

        $transactionhistory->per_balance = $account->main_balance;
        $transactionhistory->save();
    }

    $notify[] = ['success', 'Selected Salary Payment Added'];
    return to_route('admin.salarypaymenthistory.index')->withNotify($notify);
}


    public function show(SalaryPaymentHistory $salarypaymenthistory)
    {
        Gate::authorize('admin.salarypaymenthistory.show');
        return view('admin.SalaryPaymentHistory.show', compact('salarypaymenthistory'));
    }

    public function edit(SalaryPaymentHistory $salarypaymenthistory)
    {
        Gate::authorize('admin.salarypaymenthistory.edit');
        $data['employees'] = Employee::get(['id', 'emp_id', 'name']);
        $data['months']         = Month::get();
        $data['years']         = Year::get();
        return view('admin.hr.salarypayments.edit', compact('salarypaymenthistory'), $data);
    }

    public function update(Request $request, SalaryPaymentHistory $salarypaymenthistory)
    {
        Gate::authorize('admin.salarypaymenthistory.update');

        $request->validate([
            'amount' => 'required',
        ]);

        $salarypaymenthistory->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Salary Payment Updated'];
        return to_route('admin.salarypaymenthistory.index')->withNotify($notify);
    }

    public function destroy(SalaryPaymentHistory $salarypaymenthistory)
    {
        Gate::authorize('admin.salarypaymenthistory.destroy');

        SalaryGenerate::where('id', $salarypaymenthistory->salary_generate_id)->update(['status' => 'Generated']);

        $salarypaymenthistory->delete();

        $notify[] = ['success', "Salary Payment History deleted successfully"];
        return back()->withNotify($notify);
    }


    public function singleemployeesalary()
    {
        $data['departments']    = Department::orderBy('position', 'ASC')->get();
        $data['months']         = Month::get();



        return view('admin.hr.salarypayments.single_payment', $data);
    }

    public function getUnpaidSalary(Request $request)
    {

        $salary_generate = SalaryGenerate::where('status', 'Generated')->where('employee_id', $request->employee)->get();

        $datas = [];

        foreach ($salary_generate as $value) {
            $datas[] = [
                'id' => $value->id,
                'month' => $value->month->name . '-' . $value->year->name . ' (' . $value->payable_amount . ')',
            ];
        }

        return response()->json($datas);
    }


    public function singlestore(Request $request)
    {
        $request->validate([
            'account_id'            => 'required',
            'salary_generate_id'    => 'required',
            'amount'                => 'required',
        ]);

        $history = SalaryPaymentHistory::create(array_merge($request->except('department_id'), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Paid'
        ]));

        $history->invoice_no = 'SP0' . $history->id;
        $history->save();

        $salarygenerate = SalaryGenerate::find($request->salary_generate_id);
        $salarygenerate->status = $salarygenerate->salarypayment->sum('amount') == $salarygenerate->payable_amount ? "Paid" : "Generated";
        $salarygenerate->save();

        $transactionhistory = new TransactionHistory();
        $transactionhistory->date = $request->date ? $request->date : Date('Y-m-d');
        $transactionhistory->invoice_no = $history->invoice_no;
        $transactionhistory->reference_no = '';
        $transactionhistory->module_id  = 6; // Salary Expense Payment
        $transactionhistory->module_invoice_id = $history->id;
        $transactionhistory->amount     = $request->amount;
        $transactionhistory->cdf_type   = 'debit';
        $transactionhistory->payment_method_id = $request->payment_method_id;
        $transactionhistory->account_id = $request->account_id;
        $transactionhistory->client_id  = $history->employee_id;
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


        $notify[] = ['success', 'Salary Payment Added'];
        return to_route('admin.salarypaymenthistory.index')->withNotify($notify);
    }
}
