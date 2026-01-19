<?php

namespace App\Models\Order;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\PurchaseReturnPayment;

class PurchaseReturn extends Model
{
    use Searchable;

    protected $guarded = [];

    public function purchasereturndetail()
    {
        return $this->hasMany(PurchaseReturnDetail::class,'purchase_return_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchse::class, 'purchase_id');
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

    public function paidamount($id)
    {
        return PurchaseReturnPayment::where('purchase_return_id',$id)->sum('amount');
    }
}
