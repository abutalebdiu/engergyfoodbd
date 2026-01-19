<?php

namespace App\Models\Order;

use App\Models\Admin;
use App\Models\User;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class BuyerHotel extends Model
{
    use Searchable;


    protected $guarded = [];



    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }



    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
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
