<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAssessmentEntry extends Model
{
    use HasFactory;
     protected $fillable = [
        'assessment_id',
        'emp_id',
        'self_score',
        'manager_score',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
