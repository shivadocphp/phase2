<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReviewEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'review_id',
        'emp_id',
        'answer',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
