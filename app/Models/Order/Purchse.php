<?php

namespace App\Models\Order;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\OrderSupplierPayment;
use App\Models\Product\Product;

class Purchse extends Model
{
    use Searchable;

    protected $guarded = [];


    public function purchasedetail()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
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

    public function paidamount($id)
    {
        return OrderSupplierPayment::where('purchase_id',$id)->sum('amount');
    }
}
