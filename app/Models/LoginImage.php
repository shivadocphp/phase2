<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginImage extends Model
{
    use HasFactory;
    protected $fillable = ['image_location','current_image'];
}
