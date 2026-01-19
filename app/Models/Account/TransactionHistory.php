<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\HR\Employee;
use App\Models\HR\Marketer;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'client_id');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class,'client_id');
    }

    public function moduletype()
    {
        return $this->belongsTo(ModuleType::class, 'module_id');
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
