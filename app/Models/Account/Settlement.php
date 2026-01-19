<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use Searchable;

    protected $guarded = ['id'];

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
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
