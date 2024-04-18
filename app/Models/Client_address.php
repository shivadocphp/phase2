<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_address extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'address',
        'state_id',
        'city_id',
        'country_id',
        'pincode',
        'start_mon_year',
        'gst',
        'address_type',
        'added_by',
        'updated_by'
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City','city_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }
}
