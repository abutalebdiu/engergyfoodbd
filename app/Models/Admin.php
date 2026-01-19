<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Searchable, GlobalStatus;

    protected $guard = 'admin';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permissionCode): bool
    {
        return $this->role->permission()->where('code', $permissionCode)->exists();
    }
}
