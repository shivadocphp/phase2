<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_leave extends Model
{
    use HasFactory;
    protected $fillable=['emp_code','leavetype_id','year','month','requested_days','required_from','required_to',
    'reason','total_hours_leave','approved_days','approved_from','approved_to','leave_status','comments','added_by','updated_by','created_at','updated_at'];


    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User','added_by');
    }

    public function leavetype()
    {
        return $this->belongsTo('App\Models\Leavetype','leavetype_id');
    }
}
