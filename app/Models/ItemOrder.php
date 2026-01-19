<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use Searchable;

    protected $guarded = [];


    public function itemOrderDetail()
    {
        return $this->hasMany(ItemOrderDetail::class, 'item_order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'supplier_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class,'supplier_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
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
        return ItemOrderPayment::where('item_order_id',$id)->sum('amount');
    }
    
    public function itemorderpayments()
    {
        return $this->hasMany(ItemOrderPayment::class,'item_order_id');
    }

   
}
