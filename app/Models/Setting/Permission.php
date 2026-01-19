<?php

namespace App\Models\Setting;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $guarded = ["id"];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
