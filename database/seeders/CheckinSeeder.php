<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Checkin;
use App\Models\User;
use App\Models\Activity;
use Carbon\Carbon; // 引入 Carbon 类

class CheckinSeeder extends Seeder
{
    public function run(): void
    {
        $volunteer = User::where('role', 'volunteer')->first();
        $activity = Activity::first();

        Checkin::create([
            'activity_id' => $activity->id,
            'user_id' => $volunteer->id,
            // 手动将时间字符串转换为 Carbon 对象，再减去30分钟
            'timestamp' => Carbon::parse($activity->time)->subMinutes(30),
        ]);
    }
}