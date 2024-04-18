<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'is_active',
        'created_at',
        'updated_at'
    ];

}
