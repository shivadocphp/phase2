<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_code','attendance_date','login_time','morning_break_in','morning_break_out','lunch_break_in','lunch_break_out',
        'evening_break_in','evening_break_out','logout_time','total_working_hours','total_break_hours','added_by','updated_by',
        'created_at','updated_at'
    ];
}
