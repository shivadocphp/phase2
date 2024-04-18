<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_bank_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id','emp_code','bank_name','account_no','ifsc_code','branch_code',
        'added_by','updated_by','created_at','updated_at',

    ];
}
