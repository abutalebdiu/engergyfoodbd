<?php

namespace App\Models;

use App\Models\Account\Account;
use App\Models\Account\PaymentMethod;
use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ItemOrderPayment extends Model
{
    use Searchable;

    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Paid', 'Unpaid']);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function item()
    {
        return $this->belongsTo(ItemOrder::class, 'item_order_id', 'id');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
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
