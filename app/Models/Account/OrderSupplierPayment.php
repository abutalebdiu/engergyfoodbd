<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;

use App\Models\Order\Purchse;
use Illuminate\Database\Eloquent\Model;

class OrderSupplierPayment extends Model
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
    public function purchase()
    {
        return $this->belongsTo(Purchse::class, 'purchase_id');
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
