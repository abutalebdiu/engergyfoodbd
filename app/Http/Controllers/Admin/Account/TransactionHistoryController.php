<?php


namespace App\Http\Controllers\Admin\Account;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\TransactionHistory;


class TransactionHistoryController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('admin.transactionhistory.list');
        $data['customers']              = User::where('type','customer')->get();

        $query = TransactionHistory::query();

        if($request->customer_id)
        {
            $query->where('client_id',$request->customer_id);
        }

        if ($request->start_date && $request->end_date) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subHours(40));
        }
 
        $data['transactionhistories']   = $query->active()->latest()->paginate(100);
        
        return view('admin.accounts.transactionhistories.view', $data);
    }

    public function create()
    {
        Gate::authorize('admin.transactionhistory.create');
        return view('admin.TransactionHistory.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.transactionhistory.store');
        $request->validate([
            'name' => 'required',
        ]);

        TransactionHistory::create(array_merge($request->all(), [
            'entry_id' => auth('admin')->user()->id,
            'status' => 'Active'
        ]));

        $notify[] = ['success', 'TransactionHistory successfully Added'];
        return redirect('admin.TransactionHistory.index')->withNotify($notify);
    }

    public function show(TransactionHistory $transactionHistory)
    {
        Gate::authorize('admin.transactionhistory.show');
        return view('admin.TransactionHistory.show', compact('transactionHistory'));
    }

    public function edit(TransactionHistory $transactionHistory)
    {
        Gate::authorize('admin.transactionhistory.edit');
        return view('admin.TransactionHistory.edit', compact('transactionHistory'));
    }

    public function update(Request $request, TransactionHistory $transactionHistory)
    {
        Gate::authorize('admin.transactionhistory.update');
        $request->validate([
            'name' => 'required',
        ]);

        $transactionHistory->update(array_merge($request->all(), [
            'edit_id' => auth('admin')->user()->id,
            'edit_at' => now(),
        ]));

        $notify[] = ['success', 'TransactionHistory successfully Updated'];
        return to_route('admin.TransactionHistory.index')->withNotify($notify);
    }

    public function destroy(TransactionHistory $transactionHistory)
    {
        Gate::authorize('admin.transactionhistory.destroy');
        $transactionHistory->delete();
        $notify[] = ['success', "TransactionHistory deleted successfully"];
        return back()->withNotify($notify);
    }
}
