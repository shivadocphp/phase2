<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateBasicDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id',
        'candidate_name',
        'gender', 'employement_mode', 'pf_status', 'preferred_shift', 'communication', 'candidate_resume',
        'contact_no', 'whatsapp_no', 'candidate_email', 'profile_source',
        'designation', 'current_salary', 'expected_salary',
        'total_exp', 'notice_period', 'duration', 'quali_level_id',
        'quali_id', 'current_location', 'preferred_location', 'passport', 'skills',
        'candidate_status', 'added_by', 'updated_by', 'deleted_by'
    ];


    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User', 'added_by');
    }
}
