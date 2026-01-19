<?php

namespace App\Http\Controllers\Admin\HR;

use App\Models\HR\Employee;
use App\Models\Setting\Year;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Setting\Month;
use App\Models\Account\Account;
use App\Models\HR\SalaryAdvance;
use App\Http\Controllers\Controller;
use App\Models\Account\TransactionHistory;
use PDF;
use Carbon\Carbon;

class SalaryAdvanceController extends Controller
{

    public function index(Request $request)
    {

        $data['months'] = Month::get();
        $data['employees'] = Employee::where('status', 'Active')->get();
        $data['years'] = Year::orderBy('name', 'DESC')->get();

        $data['departments'] = Department::orderBy('position', 'ASC')->get();

        $query = SalaryAdvance::with(['employee.department', 'month', 'year', 'paymentmethod', 'account'])
                                        ->join('employees', 'employees.id', '=', 'salary_advances.employee_id')
                                        ->select('salary_advances.*');

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->whereBetween('date', [Carbon::parse(date('Y-m-01')), Carbon::parse(date('Y-m-t'))]);
        }

        if ($request->year_id) {
            $query->where('year_id', $request->year_id);
        }

        if ($request->month_id) {
            $query->where('month_id', $request->month_id);
        } else {
            $query->where('month_id', Date('m'));
        }

        if ($request->department_id) {
            $query->where('employees.department_id', $request->department_id);
        }

        $data['salaryadvances'] = $query->get()
            ->groupBy(function ($item) {
                return $item->month->name . '-' . $item->year->name;
            })
            ->map(function ($group) {
                return $group->groupBy('employee.department.name');
            });



        if ($request->has('search')) {
            return view('admin.hr.salaryadvances.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.hr.salaryadvances.salary_advance_pdf', $data);
            return $pdf->stream('salary_advance.pdf');
        } elseif ($request->has('excel')) {
            // return Excel::download(new OrderExport($data), 'Order_list.xlsx');
        } else {
            return view('admin.hr.salaryadvances.view', $data);
        }
    }

    public function create()
    {
        $data['departments'] = Department::orderBy('position', 'ASC')->get();
        $data['months'] = Month::get();
        $data['employees'] = Employee::where('status', 'Active')->get();
        $data['years'] = Year::orderBy('name', 'DESC')->get();
        return view('admin.hr.salaryadvances.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'month_id'      => 'required',
            'account_id'    => 'required',
            'employee_id'   => 'required'
        ]);

        $advance = SalaryAdvance::create(array_merge($request->except('department_id'), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Paid'
        ]));
        $advance->invoice_no = 'SA0' . $advance->id;
        $advance->save();

        if ($request->type == "Regular") {

            $transactionhistory = new TransactionHistory();
            $transactionhistory->invoice_no = $advance->invoice_no;
            $transactionhistory->reference_no = '';
            $transactionhistory->module_id  = 12; // Salary Advance Payment
            $transactionhistory->module_invoice_id = $advance->id;
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
        }


        $notify[] = ['success', 'Salary Advance successfully Added'];
        return redirect()->route('admin.salaryadvance.index')->withNotify($notify);
    }

    public function show(SalaryAdvance $salaryadvance)
    {
        return view('admin.hr.salaryadvances.show', compact('salaryAdvance'));
    }

    public function edit(SalaryAdvance $salaryadvance)
    {
        $data['departments'] = Department::orderBy('position', 'ASC')->get();
        $data['months']     = Month::get();
        $data['employees']  = Employee::get();
        $data['years'] = Year::orderBy('name', 'DESC')->get();
        return view('admin.hr.salaryadvances.edit', compact('salaryadvance'), $data);
    }

    public function update(Request $request, SalaryAdvance $salaryadvance)
    {
        $request->validate([
            'account_id' => 'required',
            'employee_id' => 'required'
        ]);

        $salaryadvance->update(array_merge($request->except('department_id'), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'Salary Advance successfully Updated'];
        return to_route('admin.salaryadvance.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $salaryadvance = SalaryAdvance::find($id);
        $salaryadvance->delete();
        
        $notify[] = ['success', "Salary Advance deleted successfully"];
        return back()->withNotify($notify);
    }
}
