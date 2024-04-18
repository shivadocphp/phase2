<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable=['company_name','company_address',
    'gstin','cgst','sgst','igst','email_id','landline_no','mobile_no','added_by','updated_by',
        'deleted_by','created_at','updated_at','deleted_at'];
}
