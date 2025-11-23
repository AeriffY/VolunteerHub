<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'timestamp', //timestamp这里指的是签到时间
    ];
    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public $timestamps = true; // 自动维护created_at和updated_at字段


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
