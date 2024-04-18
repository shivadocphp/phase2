<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_official_detail extends Model
{
    use HasFactory;
    protected $fillable = [
       'emp_id','employee_code','employementmode_id','joining_date','department_id','designation_id',
        'official_emailid','esic_no','pf_no','uan_no','relieving_date','bgv','comments','team_id',
        'added_by','updated_by','created_at','updated_at',

    ];

}
