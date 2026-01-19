<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Paid', 'Unpaid']);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
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
