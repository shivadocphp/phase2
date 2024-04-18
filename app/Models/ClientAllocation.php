<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAllocation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=  ['id','client_id','team_id','created_by','updated_by','created_at','updated_at','deleted_by'];

    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User','added_by');
    }
    
}
