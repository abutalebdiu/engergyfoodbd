<?php

namespace App\Http\Controllers\Admin\Order;

use PDF;
use App\Models\User;
use App\Constants\Status;
use App\Models\ItemOrder;
use Illuminate\Http\Request;
use App\Models\Order\Purchse;
use Illuminate\Support\Carbon;
use App\Models\NotificationLog;
use App\Models\ItemOrderPayment;
use App\Http\Controllers\Controller;
use App\Models\Order\PurchaseReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Models\Account\SupplierDuePayment;
use App\Models\Account\OrderSupplierPayment;

class ManageSupplierController extends Controller
{
    public function allUsers(Request $request)
    {
        Gate::authorize('admin.supplier.all');

        $users = User::where('type', 'supplier')->orderBy('name', 'asc')->get();

        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.users.list_pdf', ['users' => $users]);
            return $pdf->stream('suppliers_list.pdf');
        } else {
            return view('admin.users.list', compact('users'));
        }
    }


    public function activeUsers()
    {
        Gate::authorize('admin.supplier.active');
        $title = 'Active Supplier';
        $users = $this->userData('active');
        return view('admin.users.list', compact('title', 'users'));
    }

    public function bannedUsers()
    {
        Gate::authorize('admin.supplier.banned');
        $title = 'Banned Supplier';
        $users = $this->userData('banned');
        return view('admin.users.list', compact('title', 'users'));
    }

    public function create()
    {
        Gate::authorize('admin.supplier.create');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.create', compact('countries'));
    }


    public function store(Request $request)
    {
        Gate::authorize('admin.supplier.store');
        $user = new User();

        $request->validate([
            'name' => 'required'
        ]);

        $user->type = 'supplier';
        $user->uid          = 0;
        $user->company_name = $request->company_name;
        $user->mobile       = bn2en($request->mobile);
        $user->name         = $request->name;
        $user->address      = $request->address;
        $user->opening          = bn2en($request->opening);
        $user->opening_due      = bn2en($request->opening);
        $user->save();

        $user->uid          = $user->id;
        $user->save();

         if($request->ajax()){
            return response()->json([
                "status" => true,
                "message" => __("Supplier successfully added!"),
                "redirect" => route('admin.suppliers.detail', $user->id),
            ], 201);
        }

        $notify[] = ['success', 'Supplier Add successfully'];
        return back()->withNotify($notify);
    }





    protected function userData($scope = null)
    {
        if ($scope) {
            $users = User::where('type', 'supplier')->$scope();
        } else {
            $users = User::where('type', 'supplier');
        }
        return $users->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }


    public function detail($id)
    {
        Gate::authorize('admin.supplier.detail');
        $user = User::findOrFail($id);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.detail', compact('user', 'countries'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin.supplier.update');
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'nullable|email|string|max:40|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:40|unique:users,mobile,' . $user->id,

        ]);
        $user->company_name = $request->company_name;
        $user->mobile = bn2en($request->mobile);
        $user->name = $request->name;
        $user->address = $request->address;
        $user->opening      = bn2en($request->opening);
        $user->opening_due = bn2en($request->opening);
        $user->save();

        $notify[] = ['success', 'Supplier details updated successfully'];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $user->status = Status::USER_BAN;
            $user->ban_reason = $request->reason;
            $notify[] = ['success', 'User banned successfully'];
        } else {
            $user->status = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $notify[] = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        return view('admin.users.notification_single', compact('user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $notifyToUser = User::notifyToUser();
        $users = User::active()->count();
        return view('admin.users.notification_all', compact('users', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
            'start' => 'required',
            'batch' => 'required',
            'being_sent_to' => 'required',
            'user' => 'required_if:being_sent_to,selectedUsers',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedUsers|integer|gte:0',
            'number_of_days' => 'required_if:being_sent_to,notLoginUsers|integer|gte:0',
        ], [
            'number_of_days.required_if' => "Number of days field is required",
            'number_of_top_deposited_user.required_if' => "Number of top deposited user field is required",
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->all()]);

        $scope = $request->being_sent_to;
        $users = User::oldest()->active()->$scope()->skip($request->start)->limit($request->batch)->get();
        foreach ($users as $user) {
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
        }
        return response()->json([
            'total_sent' => $users->count(),
        ]);
    }

    public function list()
    {
        Gate::authorize('admin.supplier.list');
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'users' => $users,
            'more' => $users->hasMorePages()
        ]);
    }

    public function notificationLog($id)
    {
        $user = User::findOrFail($id);
        $logs = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('logs', 'user'));
    }




    /**
     * This function provides the statement of a supplier. It includes the supplier details, transaction histories, total orders, total amount, total returns, total due and total advance.
     *
     * @param int $id The id of the supplier.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view of the supplier statement.
     */
    public function statement(Request $request, $id)
    {
        Gate::authorize('admin.supplier.statement');

        $start_date = $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $data['supplier'] = $this->getSupllier($id);
        $data['suppliersduepayments']   = $this->getSuppliersDuePaymentHistories($id, $start_date, $end_date);
        $data['transactionhistories'] = $this->getTransactionHistories($id, $start_date, $end_date);
        $data['itemorders'] = $this->getItemOrders($id, $start_date, $end_date);
        $data['totalorders'] = $this->getTotalOrders($id, $start_date, $end_date);
        $data['totalamount'] = $this->getTotalAmount($id, $start_date, $end_date);
        $data['totalitempayment'] = $this->getTotalItemPayment($id, $start_date, $end_date);
        $data['totalsupplierduepayment'] = $this->getSupplierDuePayment($id, $start_date, $end_date);
        $data['totalreturns'] = $this->getTotalReturns($id, $start_date, $end_date);
        $data['total_due'] = $this->calculateTotalDue($id, $start_date, $end_date);
        $data['total_advance'] = $this->getAdvance($id, $start_date, $end_date);
        $data['used_advance'] = $this->usedAdvance($id, $start_date, $end_date);
        $data['purchse'] = Purchse::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->with('purchasedetail')->first();

        if($request->ajax()){
            if($request->tab == 'v-pills-itempurchase-tab'){
                return response()->json([
                    'success' => true,
                    'data' => view('admin.users.partials._item_orders', $data)->render()
                ]);
            }
            elseif ($request->tab == 'v-pills-transaction-tab'){
                return response()->json([
                    'success' => true,
                    'data' => view('admin.users.partials.__transaction_history', $data)->render()
                ]);
            }else if($request->tab == 'v-pills-payment-tab'){
                return response()->json([
                    'success' => true,
                    'data' => view('admin.users.partials.__payment_history', $data)->render()
                ]);
            }else if($request->tab == 'v-pills-duepayment-tab'){
                return response()->json([
                    'success' => true,
                    'data' => view('admin.users.partials.__due_payment_history', $data)->render()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid tab'
                ]);
            }
        }


        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        return view('admin.users.statement', $data);
    }

    private function getSupllier($id)
    {
        return User::findOrFail($id);
    }

    private function getTransactionHistories($id, $start_date, $end_date)

    {
        return \App\Models\Account\TransactionHistory::where('client_id', $id)->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'desc')->paginate(getPaginate());
    }
    
    
    private function getItemOrders($id, $start_date, $end_date)
    {
        return ItemOrder::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->get();
    }


    private function getSuppliersDuePaymentHistories($id, $start_date, $end_date)
    {
        return SupplierDuePayment::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->get();
    }

    private function getTotalOrders($id, $start_date, $end_date)
    {
        return ItemOrder::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->count() ?? 0;
    }

    private function getTotalAmount($id, $start_date, $end_date)
    {
        return ItemOrder::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('totalamount') ?? 0;
    }

    private function getTotalItemPayment($id, $start_date, $end_date)
    {
        return ItemOrderPayment::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount') ?? 0;
    }

    private function getSupplierDuePayment($id, $start_date, $end_date)
    {
        return SupplierDuePayment::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount') ?? 0;
    }


    /**
     * Get the total amount of payable for the supplier.
     *
     * @param int $id
     * @return int
     */
    private function getPayable($id, $start_date, $end_date)
    {
        $totalAmount = $this->getSupllier($id)->opening;
        return $totalAmount ?? 0;
    }


    /**
     * Get the total amount of order supplier payment of the supplier.
     *
     * @param int $id
     * @return int
     */
    private function getOrderSupplierPayment($id, $start_date, $end_date)
    {
        $amount = ItemOrderPayment::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount');
        return $amount ?? 0;
    }


    /**
     * Get the total amount of returns of the supplier.
     *
     * @param int $id
     * @return int
     */
    private function getTotalReturns($id, $start_date, $end_date)
    {
        $totalAmount = PurchaseReturn::where('supplier_id', $id)->sum('amount');
        return $totalAmount ?? 0;
    }

    /**
     * Get the total amount of advance given to the supplier.
     *
     * @param int $id
     * @return int
     */
    private function getAdvance($id, $start_date, $end_date)
    {
        $advance = \App\Models\Account\SupplierAdvance::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount');
        return $advance ?? 0;
    }

    /**
     * Get the total amount of advance that has been used by the supplier.
     *
     * @param int $id
     * @return int
     */
    private function usedAdvance($id, $start_date, $end_date)
    {
        $advance = \App\Models\Account\SupplierAdvance::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('used_amount');
        return $advance ?? 0;
    }

    private function calculateTotalDue($id, $start_date, $end_date)
    {
        $totalAmount = $this->getTotalAmount($id, $start_date, $end_date);
        $getPayable = $this->getPayable($id, $start_date, $end_date);

        // balance is negative to show in zero
        $balance = ($totalAmount + $getPayable) - $this->payableBalance($id, $start_date, $end_date);
        return $balance > 0 ? $balance : 0;
    }

    /**
     * Get the total amount of balance that the supplier is payable.
     *
     * The balance is the total of the paid amount, the return amount, the less amount and the advance given to the supplier.
     *
     * @param int $id The id of the supplier.
     * @return int
     */
    private function payableBalance($id, $start_date, $end_date)
    {
        $paidAmount = $this->getOrderSupplierPayment($id, $start_date, $end_date);
        $returnAmount = $this->getTotalReturns($id, $start_date, $end_date);
        return ($paidAmount + $returnAmount + $this->getLessAmount($id, $start_date, $end_date) + $this->getAdvance($id, $start_date, $end_date)) ?? 0;
    }

    private function getLessAmount($id, $start_date, $end_date)
    {
        $lessAmount = OrderSupplierPayment::where('supplier_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('less_amount');
        return $lessAmount ?? 0;
    }
}
