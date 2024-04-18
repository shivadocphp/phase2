<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_team extends Model
{
    use HasFactory;
    protected $fillable = ['team_id','emp_id','emp_code','member_type','comments','is_active','added_by','updated_by','created_at','updated_at','deleted_at'];
}
