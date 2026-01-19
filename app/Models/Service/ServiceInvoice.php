<?php

namespace App\Models\Service;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Setting\Year;
use App\Models\Setting\Month;
use Illuminate\Database\Eloquent\Model;

class ServiceInvoice extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Generated','Paid','Canceled']);
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
    
    public function month()
    {
        return $this->belongsTo(Month::class,'month_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class,'year_id');
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
