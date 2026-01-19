<?php

namespace App\Models\Expense;

use App\Models\Admin;
use App\Models\HR\Employee;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Paid', 'Unpaid']);
    }
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
    public function expenseby()
    {
        return $this->belongsTo(Employee::class, 'expense_by');
    }

    public function expensepayment()
    {
        return $this->hasMany(ExpensePaymentHistory::class,'expense_id');
    }

    public function expensedetail()
    {
        return $this->hasMany(ExpenseDetail::class,'expense_id');
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

    public function paidamount($id)
    {
        return ExpensePaymentHistory::where('expense_id',$id)->sum('amount');
    }
}
