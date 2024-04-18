<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_target extends Model
{
    use HasFactory;
    protected $fillable = ['emp_id','emp_code','target','achieved','month','year','added_by','updated_by','created_at','updated_at','deleted_at'];
}
