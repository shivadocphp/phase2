<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAssessment extends Model
{
    use HasFactory;
    protected $fillable = [
        'assessment_parameter',
        'desription',
        'weightage',
        'is_active',
        'created_at',
        'updated_at'
    ];

}
