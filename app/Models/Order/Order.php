<?php

namespace App\Models\Order;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\HR\Employee;
use App\Models\HR\Marketer;
use App\Models\Account\OrderPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Distribution\Distribution;

class Order extends Model
{
    use Searchable;

    protected $guarded = [];


    public function orderdetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderpayments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    public function orderreturn()
    {
        return $this->hasMany(OrderReturn::class, 'order_id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class, 'marketer_id');
    }

    public function salesman()
    {
        return $this->belongsTo(Employee::class, 'salesman_id');
    }

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
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
        return OrderPayment::where('order_id', $id)->sum('amount');
    }


    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id', 'id');
    }

}
