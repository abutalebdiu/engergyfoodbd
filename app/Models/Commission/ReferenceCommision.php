<?php

namespace App\Models\Commission;

use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class ReferenceCommision extends Model
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

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }


}
