<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $fillable=['invoice_id',
        'description','sac_code','amount','added_by','updated_by',
        'deleted_by',
        'created_at','updated_at','deleted_at'];
}
