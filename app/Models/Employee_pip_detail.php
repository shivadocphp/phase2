<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_pip_detail extends Model
{
    use HasFactory;
    protected $fillable=['emp_id','emp_code','first_review','second_review','third_review','review_comment','added_by','updated_by',
        'created_at','updated_at'];
}
