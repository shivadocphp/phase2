<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'location',
        'position',
        'total_position',
        'min_years',
        'max_years',
        'matriculation',
        'plustwo',
        'quali_level_id',
        'quali_id',
        'salary_min',
        'salary_max',
        'cab_facility',
        'hiring_radius',
        'role_type',
        'employement_type',
        'domain',
        'requirement_status',
        'skills',
        'jd',
        'targeted_companies',
        'nonpatch_companies',
        'interview_rounds',
        'open_till',
        'no_consultant',
        'bond',
        'bond_years',
        'tat',
        'added_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User','added_by');
    }

}
