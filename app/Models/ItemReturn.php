<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ItemReturn extends Model
{
    use Searchable;

    protected $guarded = [];


    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(ItemOrder::class,'order_id', 'id');
    }

    public function orderreturndetail()
    {
        return $this->hasMany(ItemOrderReturnDetail::class,'order_return_id');
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
        return ItemReturnPayment::where('order_return_id',$id)->sum('amount');
    }
}
