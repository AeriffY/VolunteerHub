<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'registration_time',

        'status',
    ];

    protected $casts = [
        'registration_time' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'registered',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
