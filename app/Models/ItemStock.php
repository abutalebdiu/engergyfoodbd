<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Setting\Month;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ItemStock extends Model
{
    use Searchable;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function month()
    {
        return $this->belongsTo(Month::class, 'month_id');
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
