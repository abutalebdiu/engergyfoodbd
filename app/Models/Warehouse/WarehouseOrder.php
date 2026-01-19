<?php

namespace App\Models\Warehouse;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use Illuminate\Database\Eloquent\Model;

class WarehouseOrder extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function orderdetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
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
}
