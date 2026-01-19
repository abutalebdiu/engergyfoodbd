<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ItemOrderDetail extends Model
{
    use Searchable;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Item::class,'item_id');
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

    public function orderreturndetail(){
        return $this->hasMany(ItemOrderReturnDetail::class,'order_detail_id');
    }
    
    public function itemOrderList(){
        return $this->belongsTo(ItemOrder::class,'item_order_id');
    }
}
