<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /*用户发布的活动,仅管理员*/
    public function activities()
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    /*用户报名记录 */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /*用户签到记录 */
    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function hours(){
        return $this->hasOne(Hours::class);
    }
}
