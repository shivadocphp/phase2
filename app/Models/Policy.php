<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $fillable = ['policy','document','is_active','created_date','created_by','updated_date','updated_by'];
}