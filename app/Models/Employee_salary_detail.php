<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_salary_detail extends Model
{
    use HasFactory;
    protected $fillable=['emp_id','emp_code','fixed_basic','fixed_hra','fixed_conveyance',
        'employer_pf','employer_esi','employee_pf','employee_pf','casual_leave_available','monthly_target','start_date','comments','added_by','updated_by',
        'created_at','updated_at'];
}
