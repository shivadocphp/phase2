<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_code extends Model
{
    use HasFactory;
    protected $fillable=['emp_code','surname','created_at','updated_at'];
}
