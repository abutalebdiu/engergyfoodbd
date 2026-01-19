<?php

namespace App\Models\Distribution;

use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;
 
class Distribution extends Model
{
    use Searchable;

    protected $guarded = [];

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
    
    
    public function distributionorderpayments()
    {
        return $this->hasMany(DistributionOrderPayment::class,'distribution_id');
    }
    
   

    public function orders()
    {
        return $this->hasMany(Order::class,'distribution_id');
    }

    protected static function getorderamount($distribution_id)
    {
        $total = Order::where('distribution_id', $distribution_id)->sum('grand_total');
        return $total;
    }

    protected static function getpaymentamount($distribution_id)
    {
        $total = DistributionOrderPayment::where('distribution_id', $distribution_id)->sum('amount');
        return $total;
    }

    // balance 
    public function receivable($id)
    {
        return self::getorderamount($id) - self::getpaymentamount($id);
    }
}
