<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    //
    protected $fillable = [
        'user_id',
        'activity_id',
        'status',
        'registration_time',
        'created_at',
        'updated_at' 
    ];
}
