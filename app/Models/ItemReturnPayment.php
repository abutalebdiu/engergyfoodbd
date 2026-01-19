<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Account\Account;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class ItemReturnPayment extends Model
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
        return $this->belongsTo(ItemReturn::class, 'order_return_id');
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
