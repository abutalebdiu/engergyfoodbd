<?php

namespace App\Models\Commission;

use App\Models\User;
use App\Models\Admin;
use App\Models\Setting\Month;
use App\Traits\Searchable;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class CommissionInvoice extends Model
{
    use Searchable;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function month()
    {
        return $this->belongsTo(Month::class, 'month_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'commission_invoice_id','id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
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
