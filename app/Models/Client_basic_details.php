<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_basic_details extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'company_emailID',
        'company_contact_no',
        'industry_type_id',
        'ceo',
        'ceo_contact',
        'ceo_emailID',
        'hr_spoc',
        'hr_desig',
        'fspoc',
        'fspoc_designation',
        'fspoc_contact',
        'fspoc_email',
        'client_status',
        'website',
        'comments',
        'added_by',
        'updated_by',
    ];

    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User','added_by');
    }

}
