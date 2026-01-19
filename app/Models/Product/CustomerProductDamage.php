<?php

namespace App\Models\Product;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class CustomerProductDamage extends Model
{
    use Searchable;

    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customerproductdamagedetail()
    {
        return $this->hasMany(CustomerProductDamageDetail::class,'customer_product_damage_id');
    }


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
}
