<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week_off extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_code',
        'date',
        'type','added_by','updated_by','created_at','updated_at'
    ];
}
