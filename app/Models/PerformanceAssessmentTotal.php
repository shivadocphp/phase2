<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAssessmentTotal extends Model
{
    use HasFactory;
    protected $fillable = ['emp_id','financial_year','total_self_score','total_manager_score','self_comment','manager_comment','created_by','updated_by','created_at','updated_at'];
}
