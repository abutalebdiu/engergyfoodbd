<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Order\OrderReturn;
use Illuminate\Database\Eloquent\Model;

class OrderReturnPayment extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }


    public function orderreturn()
    {
        return $this->belongsTo(OrderReturn::class, 'order_return_id');
    }

    public function entryuser()
    {
        return $this->belongsTo(Admin::class,'entry_id');
    }

    public function edituser()
    {
        return $this->belongsTo(Admin::class,'edit_id');
    }

    public function deleteuser()
    {
        return $this->belongsTo(Admin::class,'deleted_id');
    }
}
