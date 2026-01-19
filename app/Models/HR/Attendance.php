<?php

namespace App\Models\HR;

 
use App\Models\Admin;
use App\Models\Setting\Month;
use App\Models\Setting\Year;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Searchable;

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
