<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\User;
use App\Models\Activity;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // 获取志愿者和活动
        $volunteer = User::where('role', 'volunteer')->first();
        $activity = Activity::first(); // 取唯一的活动

        // 创建报名记录
        Registration::create([
            'user_id' => $volunteer->id,
            'activity_id' => $activity->id,
            'registration_time' => now(), // 现在报名
            'status' => 'registered', // 已报名
        ]);
    }
}