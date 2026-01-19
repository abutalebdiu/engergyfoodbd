<?php

namespace App\Models\Commission;

use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\HR\Marketer;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class MarketerCommission extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class,'marketer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'mc_invoice_id','id');
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
    
    public function marketercommissionpayment()
    {
        return $this->hasMany(MarketerCommissionPayment::class,'marketer_commission_id');
    }
    
}
