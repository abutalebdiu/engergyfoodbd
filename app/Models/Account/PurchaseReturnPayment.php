<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Account\Account;
use App\Models\Order\PurchaseReturn;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnPayment extends Model
{
    use Searchable;

    protected $guarded = [];

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
    public function purchasereturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
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
