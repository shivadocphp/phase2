<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_personal_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle','firstname','middlename','lastname','gender','dob','personal_emailID','landline','mobile1',
        'mobile2','diff_abled','aadhaar_no','pan_no','dl_no','dl_expiry_date','quali_level_id','quali_id','p_address1',
        'p_city_id','p_state_id','p_country_id','p_address_pincode','c_address1','c_city_id','c_state_id','c_country_id','c_address_pincode',
        'blood_group','guardian_type','guardian_name','guardian_mobile','is_active','added_by','updated_by','created_at','updated_at','shift_id'

    ];


    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User','added_by');
    }

    // public function payrolls()
    // {
    //     return $this->hasMany('App\Models\EmployeePayroll','');
    // }

}
