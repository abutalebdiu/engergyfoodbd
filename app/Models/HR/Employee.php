<?php

namespace App\Models\HR;


use App\Models\User;
use App\Traits\Searchable;
use App\Models\HR\Department;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function entryuser()
    {
        return $this->belongsTo(User::class, 'entry_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function edituser()
    {
        return $this->belongsTo(User::class, 'edit_id');
    }

    public function deleteuser()
    {
        return $this->belongsTo(User::class, 'deleted_id');
    }

    public function salary()
    {
        return SalarySetup::where('employee_id', $this->id)->sum('amount');
    }

    public function salarysum($id)
    {
        return SalarySetup::where('employee_id', $id)->sum('amount');
    }


    public function salarysetup($id, $month_id, $year_id)
    {
        $findemployee = Employee::find($id);
        return ($findemployee->daily_salary * self::totalpresent($id, $month_id, $year_id)) + self::foodallowance($id, $month_id, $year_id);
    }




    public function totalpresent($id, $month_id, $year_id)
    {
        return Attendance::where('employee_id', $id)->where('month_id', $month_id)->where('year_id', $year_id)->sum('days');
    }


    public function foodallowance($id, $month_id, $year_id)
    {
        return Employee::where('id', $id)->sum('food_allowance') * self::totalpresent($id, $month_id, $year_id);
    }

    public function bonus($id, $month_id, $year_id)
    {
        return SalaryBonusSetup::where('employee_id', $id)->where('month_id', $month_id)->where('year_id', $year_id)->sum('amount');
    }

    public function loan($id, $month_id, $year_id)
    {
        $employee = Employee::find($id);
    
        if (!$employee || $employee->loan_due <= 0) {
            return 0;
        }
    
        $receivable = $employee->receiableloan($id);
    
        return $receivable >= $employee->loan_installment
            ? $employee->loan_installment
            : $receivable;
    }


    public function salarydeduction($id, $month_id, $year_id)
    {
        return SalaryDeduction::where('employee_id', $id)->where('month_id', $month_id)->where('year_id', $year_id)->sum('amount');
    }

    public function advancesalary($id, $month_id, $year_id)
    {

        return SalaryAdvance::where('employee_id', $id)->where('month_id', $month_id)->where('year_id', $year_id)->sum('amount');
    }


    public function payableamount($id, $month_id, $year_id)
    {
        return self::salarysetup($id, $month_id, $year_id)
            +  self::bonus($id, $month_id, $year_id)
            -  self::advancesalary($id, $month_id, $year_id)
            -  self::salarydeduction($id, $month_id, $year_id)
            -  self::loan($id, $month_id, $year_id);
    }


    public function unpaidsalary()
    {
        return $this->hasMany(SalaryGenerate::class, 'employee_id')->with(['month', 'year']);
    }
    
    
    public function totalloan($id)
    {
        return Loan::where('employee_id',$id)->sum('total_amount');
    }
    
    
    public function paidloan($id)
    {
        return SalaryGenerate::where('employee_id',$id)->sum('loan_amount');
    }
     

    public function receiableloan($id)
    {
        $totalloan =  self::totalloan($id);
        $totalsalarypaidwithloan = self::paidloan($id);

        return $totalloan - $totalsalarypaidwithloan;
    }
    
    
    



}
