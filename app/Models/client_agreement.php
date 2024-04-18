<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client_agreement extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'agreement',
        'added_by',
        'updated_by'
    ];

}
