<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['id','candidate_id',
        'requirement_id','client_id','interview_mode',
        'available_time','call_back_date',
        'call_back_time','call_back_status','call_status','requirement_status',
        'comments','added_by','updated_by','deleted_by'];

    public function candidate_basic_detail()
    {
        return $this->belongsTo(CandidateBasicDetail::class,'candidate_id');
    }
}
