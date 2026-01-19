<?php

namespace App\Models\HR;


use App\Models\User;
use App\Traits\Searchable;
use App\Models\HR\Employee;
use App\Models\Account\Account;
use App\Models\HR\SalaryGenerate;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class SalaryPaymentHistory extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Paid');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function salarygenerate()
    {
        return $this->belongsTo(SalaryGenerate::class, 'salary_generate_id');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function entryuser()
    {
        return $this->belongsTo(User::class, 'entry_id');
    }

    public function edituser()
    {
        return $this->belongsTo(User::class, 'edit_id');
    }

    public function deleteuser()
    {
        return $this->belongsTo(User::class, 'deleted_id');
    }
}
