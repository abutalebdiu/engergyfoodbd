<?php

namespace App\Models\HR;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Department extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id')->where('status','Active')->select('id', 'emp_id', 'name', 'department_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'department_id');
    }
}
