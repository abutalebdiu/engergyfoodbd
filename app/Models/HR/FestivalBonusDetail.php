<?php

namespace App\Models\HR;

use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class FestivalBonusDetail extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function festivalbonus()
    {
        return $this->belongsTo(FestivalBonus::class,'festival_bonus_id');
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
