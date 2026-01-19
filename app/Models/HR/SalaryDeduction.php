<?php

namespace App\Models\HR;

use App\Models\User;
use App\Models\HR\Employee;
use App\Models\Setting\Year;
use App\Models\Setting\Month;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryDeduction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function month()
    {
        return $this->belongsTo(Month::class, 'month_id');
    }
    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function entryuser()
    {
        return $this->belongsTo(User::class, 'entry_id');
    }
}
