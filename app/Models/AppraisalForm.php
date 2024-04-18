<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppraisalForm extends Model
{
    use HasFactory;
     protected $fillable = [
        'appraisal_parameter',
        'weightage',
        'is_active',
        'created_at',
        'updated_at'
    ];

}
