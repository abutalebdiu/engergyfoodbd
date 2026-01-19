<?php

namespace App\Models\Order;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\OrderReturnPayment;

class OrderReturn extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function orderreturndetail()
    {
        return $this->hasMany(OrderReturnDetail::class,'order_return_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function paidamount($id)
    {
        return OrderReturnPayment::where('order_return_id',$id)->sum('amount');
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
