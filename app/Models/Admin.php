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

    public function finduseronline($user_id)
    {
      $finduserdata =  Admin::where('last_seen_at', '>=', now()->subMinutes(10))->where('id',$user_id)->get();

      if($finduserdata->count()>0)
      {
        return 'green__notify';
      }
      else{
        return '';
      }
    }
}
