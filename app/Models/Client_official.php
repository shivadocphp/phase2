<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_official extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'service_type','date_empanelment',
        'date_renewal',
        'freezing_period','rehire_policy','profile_validity',
        'callback_date','callback_time','agreed1',
        'agreed2','agreed3','agreed4',
        'payment1','payment2','payment3','payment4',
        'replacement1','replacement2','replacement3','replacement4',
        'agreement',
        'added_by',
        'updated_by'
    ];

}
