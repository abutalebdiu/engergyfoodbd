<?php

namespace App\Http\Controllers\Admin\Order;

use PDF;
use App\Models\User;
use App\Constants\Status;
use App\Models\HR\Employee;
use App\Models\HR\Marketer;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order\Quotation;
use App\Models\Product\Product;
use App\Models\Order\OrderReturn;
use App\Http\Controllers\Controller;
use App\Models\Account\OrderPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Account\CustomerAdvance;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\TransactionHistory;
use App\Models\Commission\CommissionInvoice;
use App\Models\Commission\ReferenceCommision;
use App\Models\Distribution\Distribution;

class ManageCustomerController extends Controller
{
    public function allUsers(Request $request)
    {
        Gate::authorize('admin.customer.all');

        $query = User::query();

        // Filters
        if ($request->reference_id) {
            $query->where('reference_id', $request->reference_id);
        }
        
        if ($request->distribution_id) {
            $query->where('distribution_id', $request->distribution_id);
        }

        if ($request->uid) {
            $query->where('uid', bn2en($request->uid));
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $paginate = $request->paginate ?? 20;

        // Ajax request
        if ($request->ajax()) {
            $users = $query->where('type', 'customer')
            ->orderBy('uid', 'asc')
            ->paginate($paginate)
            ->appends(request()->query());

            return response()->json([
                "status"  => true,
                "message" => "Data retrieved successfully!",
                "html"    => view('admin.customers.partials.__customer_table', compact('users'))->render(),
            ], 200);
        }

        $marketers = Marketer::where('status', 'Active')->get();
        $distributions   = Distribution::where('status', 'Active')->get();
        // PDF Export
        if ($request->has('pdf')) {
            $users = $query->where('type', 'customer')->orderBy('uid', 'asc')->get();
            $pdf   = PDF::loadView('admin.customers.customer_list_pdf', compact('users'));
            return $pdf->stream('Customer_List.pdf');
        }

        // Normal view (with search or default)
        $users = $query->where('type', 'customer')->orderBy('uid', 'asc')->paginate($paginate)->appends(request()->query());;

        return view('admin.customers.list', compact('users', 'marketers','distributions'));
    }



    public function create()
    {
        Gate::authorize('admin.customer.create');
        $data['marketers']      = Marketer::where('status', 'Active')->get();
        $data['distributors']   = Distribution::where('status', 'Active')->get();
        return view('admin.customers.create', $data);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin.customer.store');
        $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'nullable|email|string|max:40|unique:users',
            'mobile' => 'required|string|max:40|unique:users',
        ]);

        $lastuid = User::where('type', 'customer')->max('uid');

        $user = new User();
        $user->type             = 'customer';
        $user->uid              = 0;
        $user->mobile           = bn2en($request->mobile);
        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->address          = $request->address;
        $user->company_name     = $request->company_name;
        $user->commission_type  = $request->commission_type;
        $user->commission       = bn2en($request->commission);
        $user->reference_id     = $request->reference_id;
        $user->distribution_id  = $request->distribution_id;
        $user->opening          = bn2en($request->opening);
        $user->opening_due      = bn2en($request->opening);
        $user->opening_due_status = 'Unpaid';
        $user->save();

        $user->uid          = $lastuid + 1;
        $user->save();

        $products = Product::get();

        foreach ($products as $item) {
            $refercommission = new ReferenceCommision();
            $refercommission->user_id    = $user->id;
            $refercommission->product_id = $item->id;
            $refercommission->price      = bn2en($item->sale_price);
            $refercommission->amount     = bn2en($request->commission);
            $refercommission->type       = 'Percentage';
            $refercommission->entry_id   = auth('admin')->user()->id;
            $refercommission->save();
        }
 
        if($request->ajax()){
            return response()->json([
                "status" => true,
                "message" => __("Customer successfully added!"),
                "redirect" => route('admin.customers.detail', $user->id),
            ], 201);
        }


        $notify[] = ['success', 'Customer successfully Added'];
        return back()->withNotify($notify);
    }

    public function detail($id)
    {
        Gate::authorize('admin.customer.detail');
        $user = User::findOrFail($id);
        $data['marketers']          = Marketer::where('status', 'Active')->get();
        $data['totalorders']        = Order::where('customer_id', $id)->count();
        $data['pendingorder']       = Order::where('customer_id', $id)->count();
        $data['deliveredorder']     = Order::where('customer_id', $id)->count();
        $data['distributors']       = Distribution::where('status', 'Active')->get();
        return view('admin.customers.detail', compact('user'), $data);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin.customer.update');



        $user = User::findOrFail($id);

        $oldreference = $user->reference_id;

        $request->validate([
            'name' => 'required|string|max:40',
        ]);
        $user->uid              = bn2en($request->uid);
        $user->company_name     = $request->company_name;
        $user->mobile           = bn2en($request->mobile);
        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->address          = $request->address;
        $user->commission_type  = $request->commission_type;
        $user->commission       = bn2en($request->commission);
        $user->reference_id     = $request->reference_id;
        $user->distribution_id  = $request->distribution_id;
        $user->opening          = bn2en($request->opening);
        $user->opening_due      = bn2en($request->opening);
        $user->save();


        if ($request->commission && $request->type == 2) {
            ReferenceCommision::where('user_id', $user->id)->update(['amount' => bn2en($request->commission)]);
        }

        // jodi reference change hoi tahole
        // // if($oldreference == $request->reference_id)
        // // {
        //     Order::where('customer_id', $user->id)->where('mc_invoice_id', null)->update(['marketer_id' => $request->reference_id]);
        // // }


        $notify[] = ['success', 'Customer updated successfully'];
        return to_route('admin.customers.all')->withNotify($notify);
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
            $user->status = Status::USER_BAN;

            $notify[] = ['success', 'Customer successfully Deactived'];
        } else {
            $user->status = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $notify[] = ['success', 'User successfully Active'];
        }
        $user->save();
        return back()->withNotify($notify);
    }

    public function delete(Request $request, $id)
    {
        Gate::authorize('admin.customer.destroy');
        $user = User::findOrFail($id);

        Quotation::where('customer_id', $user->id)->delete();
        Order::where('customer_id', $user->id)->delete();
        ReferenceCommision::where('user_id', $user->id)->delete();
        $user->delete();
        $notify[] = ['success', 'Customer successfully Delete'];
        return back()->withNotify($notify);
    }

    public function list()
    {
        Gate::authorize('admin.customer.list');
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $users = $query->orderBy('id', 'asc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'users' => $users,
            'more' => $users->hasMorePages()
        ]);
    }

    public function statement(Request $request, $id)
    {
        Gate::authorize('admin.customer.statement');

        $start_date = $request->start_date ?? Date('Y-m-01');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $data['customer'] = $this->getCustomer($id);
        $data['transactionhistories'] = $this->getTransactionHistories($id, $start_date, $end_date);
        $data['customerduepayments']   = $this->getCustomerDuePaymentHistories($id, $start_date, $end_date);
        $data['total_advance'] = $this->getTotalAdvance($id, $start_date, $end_date);
        $data['total_due_payment'] = $this->getTotalCustomerDue($id, $start_date, $end_date);
        $data['totalorders'] = $this->getTotalOrders($id, $start_date, $end_date);
        $data['totalreturns'] = $this->getTotalReturns($id, $start_date, $end_date);
        $data['totalamount'] = $this->getTotalAmount($id, $start_date, $end_date);
        $data['total_due'] = $this->calculateTotalDue($id, $start_date, $end_date);
        $data['total_paid'] = $this->getTotalPaidAmount($id, $start_date, $end_date);
        $data['opening_due'] = $this->getOpeningDue($id, $start_date, $end_date);
        $data['total_commission_paid'] = $this->getTotalCommission($id, $start_date, $end_date) + $this->getTotalCommissionInvoice($id, $start_date, $end_date);
        $data['orderpayments'] = OrderPayment::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->get();
 
        $data['orders'] = Order::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->latest()->get();
       
        // Merge orders and customer due payments
        $mergedData = collect();        
        // Add orders to collection
        foreach ($data['orders'] as $order) {
            $mergedData->push([
                'type' => 'order',
                'date' => $order->date,
                'data' => $order
            ]);
        }
        
        // Add customer due payments to collection
        foreach ($data['customerduepayments'] as $payment) {
            $mergedData->push([
                'type' => 'payment',
                'date' => $payment->date,
                'data' => $payment
            ]);
        }        
        // Sort by date
        $data['mergedData'] = $mergedData->sortBy('date')->values();

        //  return $this->calculateTotalDue($id);

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;


       if($request->ajax()){
            if($request->tab == 'v-pills-order-tab'){
                return view('admin.customers.partials.__order_list', $data)->render();
            } elseif($request->tab == 'v-pills-home-tab'){
                return view('admin.customers.partials.__transaction_history', $data)->render();
            } elseif($request->tab == 'v-pills-profile-tab'){
                return view('admin.customers.partials.__payment_history', $data)->render();
            } elseif($request->tab == 'v-pills-customerduepayment-tab'){
                return view('admin.customers.partials.__due_payment_history', $data)->render();
            }
       }

        return view('admin.customers.statement', $data);
    }

    private function getCustomer($id)
    {
        return User::findOrFail($id);
    }

    private function getTransactionHistories($id, $start_date, $end_date)
    {
        return TransactionHistory::active()->where('client_id', $id)->whereBetween('date', [$start_date, $end_date])->get();
    }

    private function getCustomerDuePaymentHistories($id, $start_date, $end_date)
    {
        return CustomerDuePayment::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->get();
    }

    private function getTotalAdvance($id, $start_date, $end_date)
    {
        return CustomerAdvance::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount') - CustomerAdvance::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('used_amount');
    }

    private function getTotalCustomerDue($id, $start_date, $end_date)
    {
        return CustomerDuePayment::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount');
    }

    private function getTotalOrders($id, $start_date, $end_date)
    {
        return Order::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->count();
    }

    private function getTotalReturns($id, $start_date, $end_date)
    {
        return OrderReturn::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->whereIn('payment_status', ['Unpaid', 'Partial'])->sum('net_amount');
    }

    private function getTotalAmount($id, $start_date, $end_date)
    {
        return  Order::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('net_amount');
    }

    private function getOpeningDue($id, $start_date, $end_date)
    {
        $totalAmount = $this->getCustomer($id)->opening;
        return $totalAmount;
    }


    private function getTotalPaidAmount($id, $start_date, $end_date)
    {
        $paidAmount = OrderPayment::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('amount');
        return $paidAmount;
    }

    private function getTotalOrderPaid($id, $start_date, $end_date)
    {
        $paidAmount = OrderPayment::where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->where('order_id', '!=', 0)->sum('amount');

        return $paidAmount;
    }

    public function getTotalCommission($id, $start_date, $end_date)
    {
        return Order::where('commission_status', 'Paid')->where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('commission');
    }

    public function getTotalCommissionInvoice($id, $start_date, $end_date)
    {
        return CommissionInvoice::where('payment_status', 'Unpaid')->where('customer_id', $id)->whereBetween('date', [$start_date, $end_date])->sum('commission_amount');
    }

    public function calculateTotalDue($id, $start_date, $end_date)
    {
        $customer = User::find($id);

        $totalAmount    = $this->getTotalAmount($id, $start_date, $end_date);
        $openingDue     = $this->getOpeningDue($id, $start_date, $end_date);
        $order_paid     = $this->getTotalOrderPaid($id, $start_date, $end_date);
        $commission     = $this->getTotalCommission($id, $start_date, $end_date);
        $customerduepayment         = $this->getTotalCustomerDue($id, $start_date, $end_date);

        if($customer->commission_type == "Monthly")
        {
            $totalcommissionInvoice     = $this->getTotalCommissionInvoice($id, $start_date, $end_date);
        }
        else{
            $totalcommissionInvoice = 0;
        }



        return ($totalAmount + $openingDue) - ($order_paid + $commission + $customerduepayment + $totalcommissionInvoice);
    }


    // Customer List
    public function customerlist()
    {
        Gate::authorize('admin.customer.customerlist');

        $data['users'] = User::where('type', 'customer')->orderBy('id', 'ASC')->get(['id', 'uid', 'name', 'address', 'mobile', 'company_name', 'commission_type', 'commission', 'opening_due']);
        $pdf = PDF::loadView('admin.customers.customer_list_pdf', $data);
        return $pdf->stream('Customer_List.pdf');
    }


    // Customer Commission

    public function customerproductcomissionlist($id)
    {
        Gate::authorize('admin.customer.customerproductcomissionlist');

        $data['customers'] = User::where('type', 'customer')->orderBy('id', 'ASC')->where('id', $id)->get(['id', 'uid', 'name', 'address', 'mobile', 'company_name', 'commission_type']);
        $pdf = PDF::loadView('admin.customers.customer_commission', $data);
        return $pdf->stream('customer_product_commission_list.pdf');
    }
}
