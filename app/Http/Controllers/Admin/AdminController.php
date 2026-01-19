<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Constants\Status;
use App\Models\HR\Employee;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Account\Deposit;
use App\Models\Expense\Expense;
use App\Models\Order\Quotation;
use App\Rules\FileTypeValidate;
use App\Models\ItemOrderPayment;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use App\Models\Account\OrderPayment;
use Illuminate\Support\Facades\Hash;
use App\Models\Expense\ExpenseDetail;
use App\Models\Order\QuotationDetail;
use App\Models\Account\AccountTransfer;
use App\Models\Account\TransactionHistory;
use App\Models\Account\OrderSupplierPayment;
use Auth;
class AdminController extends Controller
{
    public function dashboard(Request $request)
    {

        $start_date = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::parse(date('Y-m-01'));

        $end_date = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::parse(date('Y-m-t'));

        $dates = [];

        for ($i = $start_date->copy(); $i <= $end_date; $i->addDay()) {
            $dates[] = $i->toDateString();
        }

        $datesCollection = collect($dates);

        $formattedDates = $datesCollection->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        });


        // User Info
        $data['admins'] = Admin::whereBetween('created_at', [$start_date, $end_date])->count();
        $data['customers'] = User::whereBetween('created_at', [$start_date, $end_date])->where('type', 'customer')->count();
        $data['suppliers'] = User::whereBetween('created_at', [$start_date, $end_date])->where('type', 'supplier')->count();
        $data['employees'] = Employee::whereBetween('created_at', [$start_date, $end_date])->active()->count();

       // For count only
        $data['quotations'] = Quotation::whereDate('date', Carbon::today())
            ->active()
            ->count();

        // For total count + total amount
        $result = Quotation::whereDate('date', Carbon::today())
            ->selectRaw('COUNT(*) as total_count, SUM(net_amount) as total_amount')
            ->first();

        // Access the results
        $data['totalQuotationsCount'] = $result->total_count;
        $data['totalQuotationsAmount'] = $result->total_amount;


        $data['orders'] = Order::whereBetween('date', [$start_date, $end_date])->count();
        $data['monthlyorders'] = Order::whereMonth('created_at', Carbon::now()->month)->count();
        $data['yearlyorders'] = Order::whereYear('created_at', Carbon::now()->year)->count();





        $data['orderamounts'] = Order::whereBetween('date', [$start_date, $end_date])->sum('net_amount');
        $data['totalpayments'] = OrderPayment::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $data['todaypayments'] = OrderPayment::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $data['monthlypayments'] = OrderPayment::whereMonth('created_at', Carbon::now()->month)->sum('amount');
        $data['yearlypayments'] = OrderPayment::whereYear('created_at', Carbon::now()->year)->sum('amount');


        $data['totalsupplierpayments'] = ItemOrderPayment::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $data['todaysupplierpayments'] = ItemOrderPayment::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $data['monthlysupplierpayments'] = ItemOrderPayment::whereMonth('created_at', Carbon::now()->month)->sum('amount');
        $data['yearlysupplierpayments'] = ItemOrderPayment::whereYear('created_at', Carbon::now()->year)->sum('amount');

        $data['totalexpenses'] = Expense::whereBetween('expense_date', [$start_date, $end_date])->sum('total_amount');
        $data['todayexpenses'] = Expense::whereBetween('expense_date', [$start_date, $end_date])->sum('total_amount');
        $data['monthlyexpenses'] = Expense::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $data['yearlyexpenses'] = Expense::whereYear('created_at', Carbon::now()->year)->sum('total_amount');


        $data['availablebalance'] = Account::whereBetween('created_at', [$start_date, $end_date])->sum('main_balance');


        $data['start_date'] = $start_date->format('Y-m-d');
        $data['end_date'] = $end_date->format('Y-m-d');
        $data['formattedDates'] = $formattedDates;

        return view('admin.dashboard', $data);
    }

    public function profile()
    {
        $admin = auth('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $admin = auth('admin')->user();
        return view('admin.password', compact('admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        // if (!Hash::check($request->old_password, $user->password)) {
        //     $notify[] = ['error', 'Password doesn\'t match!!'];
        //     return back()->withNotify($notify);
        // }
        $user->password = bcrypt($request->password);
        $user->save();
        
        Auth::guard('admin')->logoutOtherDevices($request->password);
        
        $notify[] = ['success', 'Password changed successfully'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.notifications', compact('notifications'));
    }

    public function readAll()
    {
        AdminNotification::where('is_read', Status::NO)->update([
            'is_read' => Status::YES,
        ]);
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function notificationRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }


    public function orderreset()
    {
        QuotationDetail::truncate();
        Quotation::truncate();
        Order::truncate();

        $notify[] = ['success', 'Successfully Empty Database'];
        return to_route('admin.dashboard');
    }

    public function accountreset()
    {
        Account::whereIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14])->update(['opening_balance' => 0, 'main_balance' => 0]);

        TransactionHistory::truncate();
        OrderPayment::truncate();
        OrderSupplierPayment::truncate();
        Deposit::truncate();
        AccountTransfer::truncate();
        ExpenseDetail::truncate();
        Expense::truncate();

        $notify[] = ['success', 'Successfully Empty Database'];
        return to_route('admin.dashboard');
    }
}
