<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

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

    public function registrations(){
        return $this->hasMany(Registration::class);
    }

    public function checkin(){
        return $this->hasMany(Checkin::class);
    }

    //更新活动状态
    public function updateStatus(){
        if ($this->status === 'cancelled') return;
        if (now() < $this->start_time) {
            $this->status = 'published';
        } elseif (now() >= $this->start_time && now() < $this->end_time) {
            $this->status = 'in_progress';
        } else {
            $this->status = 'completed';
        }
        $this->save();
    }

    public static function updateAllStatus() {
        $now = now();
        static::where('status', 'published')->where('start_time', '<=', $now)->update(['status'=>'in_progress']);
        static::where('status', 'in_progress')->where('end_time', '<=', $now)->update(['status'=>'completed']);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
