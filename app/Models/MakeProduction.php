<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\HR\Department;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class MakeProduction extends Model
{
    use Searchable;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
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
