<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientComment extends Model
{
    use HasFactory;
    public function addedBy()
	{
	    return $this->belongsTo('App\Models\User','added_by');
	}
}
