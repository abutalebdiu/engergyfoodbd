<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constants\Status;
use App\Traits\Searchable;
use App\Traits\UserNotify;
use App\Models\HR\Employee;
use App\Models\HR\Marketer;
use App\Models\Order\Order;
use App\Models\Commission\CommissionInvoice;
use App\Models\Order\Purchse;
use App\Models\Order\OrderReturn;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Account\Settlement;
use App\Models\Product\CustomerProductDamage;
use Illuminate\Support\Facades\DB;
use App\Models\Account\OrderPayment;
use App\Models\Service\ServiceInvoice;
use App\Models\Account\CustomerAdvance;
use Illuminate\Notifications\Notifiable;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\SupplierDuePayment;
use App\Models\Account\OrderSupplierPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Distribution\Distribution;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable, UserNotify;

    protected $hidden = [
        'password',
        'remember_token',
        'kyc_data'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime',
        'kyc_data' => 'object',
    ];

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE);
    }

    public function userinfo()
    {
        return $this->hasOne(Userinfo::class, 'user_id');
    }

    public function reference()
    {
        return $this->belongsTo(Marketer::class, 'reference_id');
    }
    

    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id');
    }  
    
    // for Buyer
    public function unpaidorders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->whereIn('payment_status', ['Unpaid', 'Partial']);
    }

    // For Supplier Unpaid Order


    public function unpaidinvoice()
    {
        return $this->hasMany(ServiceInvoice::class, 'customer_id')->with(['month', 'year']);
    }


    public function balance($id)
    {
        return self::opendingdue($id);
    }

    // for customer
    public function receivable($id)
    {
        return    self::opendingdue($id)
                + self::orderdue($id)
                - self::orderpayment($id)
                - self::customerduepayment($id)
                - self::commissioninvoice($id);
    }

    public function opendingdue($id)
    {
        return User::where('id', $id)->sum('current_due');
    }

    public function orderdue($id)
    {
        return Order::where('customer_id', $id)->sum('grand_total');
    }

    public function orderreturn($id)
    {
        return OrderReturn::where('customer_id', $id)->sum('net_amount');
    }

    public function orderpayment($id)
    {
        return OrderPayment::where('customer_id', $id)->sum('amount');
    }

    public function customeradvance($id)
    {
        return CustomerAdvance::where('customer_id', $id)->sum('amount');
    }

    public function customerduepayment($id)
    {
        return CustomerDuePayment::where('customer_id', $id)->sum('amount');
    }
    
    public function commissioninvoice($id)
    {
        return CommissionInvoice::where('customer_id', $id)->sum('commission_amount');
    }


    public function settlementplus($id)
    {
        return Settlement::where('user_id', $id)->where('type', 'Plus')->sum('amount');
    }

    public function settlementminus($id)
    {
        return Settlement::where('user_id', $id)->where('type', 'Minus')->sum('amount');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }


    // Supplier
    public function payable($id)
    {
        return    self::opendingdue($id)
                + self::itemorder($id)
                - self::itemorderpayment($id)
                - self::itemorderreturn($id)
                - self::supplierduepayment($id);
    }

    public function itemorder($id)
    {
        return ItemOrder::where('supplier_id', $id)->sum('totalamount');
    }

    public function itemorderpayment($id)
    {
        return ItemOrderPayment::where('supplier_id', $id)->sum('amount');
    }

    public function itemorderreturn($id)
    {
        return ItemReturn::where('customer_id', $id)->sum('totalamount');
    }

    public function supplierorders()
    {
        return $this->hasMany(ItemOrder::class, 'supplier_id');
    }

    public function supplierspayment()
    {
        return $this->hasMany(ItemOrderPayment::class, 'supplier_id');
    }

    public function supplierduepayment($id)
    {
        return SupplierDuePayment::where('supplier_id', $id)->sum('amount');
    }
    
    
    public function orderByDate($date)
    {
        return $this->hasMany(Order::class, 'customer_id')->where('date', $date)->first();
    }
    
    public function salesThisMonth()
    {
        return $this->hasMany(Order::class, 'customer_id')
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])->sum('net_amount');
    }
     
    public function duePayments()
    {
        return $this->hasMany(CustomerDuePayment::class, 'customer_id');
    }
    
    public function productDamage()
    {
        return $this->hasMany(CustomerProductDamage::class, 'customer_id');
    }
    
    
}
