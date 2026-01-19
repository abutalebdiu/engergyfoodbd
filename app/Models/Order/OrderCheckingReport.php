<?php

namespace App\Models\Order;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Order\Order;
use App\Models\Account\Account;
use App\Models\Order\OrderDetail;
use App\Models\Account\BuyerAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Order\OrderCheckingType;
use Illuminate\Database\Eloquent\Model;

class OrderCheckingReport extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function orderdetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
    public function checkingtype()
    {
        return $this->belongsTo(OrderCheckingType::class, 'order_checking_type_id');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function buyeraccount()
    {
        return $this->belongsTo(BuyerAccount::class, 'buyer_account_id');
    }


    public function entryuser()
    {
        return $this->belongsTo(Admin::class, 'entry_id');
    }

    public function edituser()
    {
        return $this->belongsTo(Admin::class, 'edit_id');
    }

    public function deleteuser()
    {
        return $this->belongsTo(Admin::class, 'deleted_id');
    }
}
