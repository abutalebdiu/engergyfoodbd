<?php

namespace App\Http\Controllers\Admin\HR;


use Carbon\Carbon;
use App\Models\HR\Employee;
use Illuminate\Http\Request;
use App\Models\HR\Department;
use App\Models\Account\Account;
use App\Http\Controllers\Controller;
use App\Models\HR\OverTimeAllowance;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;
use PDF;

class OverTimeAllowanceController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.overtimeallowance.list');

        $data['employees'] = Employee::where('status','Active')->get();
        $query = OverTimeAllowance::query();

        if($request->employee_id)
        {
            $data['employee'] = $request->employee_id;
            $query->where('employee_id',$request->employee_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(300));
        }

        $data['overtimeallowances'] = $query->latest()->paginate(100);

        if ($request->has('search')) {
            return view('admin.hr.overtimeallowances.view', $data);
        } elseif ($request->has('pdf')) {
            $pdf =  PDF::loadView('admin.hr.overtimeallowances.view_pdf', $data);
            return $pdf->stream('order_list.pdf');
        } elseif ($request->has('excel')) {
        } else {
            return view('admin.hr.overtimeallowances.view', $data);
        }
    }

    public function create(Request $request)
    {
        Gate::authorize('admin.overtimeallowance.create');

        $data['departments'] = Department::get();
        $query = Employee::query();

        if ($request->department_id) {
            $data['department_id']      = $request->department_id;
            $query->where('department_id', $request->department_id);
            $data['searching']          = "Yes";
        } else {
            $query->where('department_id', 0);
            $data['searching']          = "No";
        }

        $data['employees'] = $query->where('status','Active')->get();

        return view('admin.hr.overtimeallowances.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.overtimeallowance.store');

        foreach ($request->employee_id as $key => $employee_id) {

            if(bn2en($request->amount[$key]) > 0 ){
                $overtimeallowance = new OverTimeAllowance();
                $overtimeallowance->date            = $request->date;
                $overtimeallowance->year            = Date('Y');
                $overtimeallowance->employee_id     = $request->employee_id[$key];
                $overtimeallowance->amount          = bn2en($request->amount[$key]);
                $overtimeallowance->payment_method_id = $request->payment_method_id;
                $overtimeallowance->account_id      = $request->account_id;
                $overtimeallowance->save();

                $overtimeallowance->invoice_no = "OTA00" . $overtimeallowance->id;
                $overtimeallowance->save();
            }

            if(bn2en($request->amount[$key]) > 0 )
            {
                $transactionhistory = new TransactionHistory();
                $transactionhistory->invoice_no = $overtimeallowance->invoice_no;
                $transactionhistory->reference_no = '';
                $transactionhistory->module_id  = 21; // Salary Advance Payment
                $transactionhistory->module_invoice_id = $overtimeallowance->id;
                $transactionhistory->amount     =  bn2en($request->amount[$key]);
                $transactionhistory->cdf_type   = 'debit';
                $transactionhistory->payment_method_id = $request->payment_method_id;
                $transactionhistory->account_id = $request->account_id;
                $transactionhistory->client_id  = $request->employee_id[$key];
                $transactionhistory->save();

                $account = Account::find($request->account_id);

                // transaction hoar ager balance
                $transactionhistory->pre_balance = $account->main_balance;
                $transactionhistory->txt_no = 'TNH000' . $transactionhistory->id;
                $transactionhistory->save();


                $account->main_balance = $account->main_balance - bn2en($request->amount[$key]);
                $account->save();

                // transaction hoar porer balance
                $transactionhistory->per_balance = $account->main_balance;
                $transactionhistory->save();
            }
        }

        $notify[] = ['success', 'Over Time Allowance successfully Added'];
        return to_route('admin.overtimeallowance.index')->withNotify($notify);
    }

    public function show(OverTimeAllowance $overtimeallowance)
    {
        Gate::authorize('admin.overtimeallowance.show');
        return view('admin.hr.overtimeallowances.show', compact('overtimeallowance'));
    }

    public function edit(OverTimeAllowance $overtimeallowance)
    {
        Gate::authorize('admin.overtimeallowance.edit');
        return view('admin.hr.overtimeallowances.edit', compact('overtimeallowance'));
    }

    public function update(Request $request, OverTimeAllowance $overtimeallowance)
    {
        Gate::authorize('admin.overtimeallowance.update');

        $request->validate([
            'name' => 'required',
        ]);

        $overTimeAllowance->update(array_merge($request->all(), [
            'edit_id'  => auth('admin')->user()->id,
            'edit_at'  => now(),
        ]));

        $notify[] = ['success', 'Over Time Allowance successfully Updated'];
        return to_route('admin.overtimeallowance.index')->withNotify($notify);
    }

    public function destroy(OverTimeAllowance $overtimeallowance)
    {
        Gate::authorize('admin.overtimeallowance.destroy');

        $overtimeallowance->delete();

        $notify[] = ['success', "Over Time Allowance deleted successfully"];
        return back()->withNotify($notify);
    }
}
