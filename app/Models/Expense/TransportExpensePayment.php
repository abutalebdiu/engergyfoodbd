<?php

namespace App\Models\Expense;

use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Account\Account;
use App\Models\Warehouse\Transport;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expense\TransportExpense;

class TransportExpensePayment extends Model
{
    use Searchable;

    protected $guarded = [];

    public function transportexpense()
    {
        return $this->belongsTo(TransportExpense::class, 'transport_expense_id');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
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
