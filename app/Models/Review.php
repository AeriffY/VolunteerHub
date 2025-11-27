<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'activity_id',
        'title',
        'content',
        'image_paths'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function activity(){
        return $this->belongsTo(Activity::class);
    }

    protected $casts = [
        'image_paths' => 'array',
    ];
}
