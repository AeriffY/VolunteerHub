<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'status',
        'created_by',
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];
    /*活动的创建者（管理员） */
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registration(){
        return $this->hasMany(Registration::class);
    }

    public function checkin(){
        return $this->hasMany(Checkin::class);
    }
}
