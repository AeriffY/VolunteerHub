<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Activity::create([
            'title' => '校园清洁志愿活动',
            'description' => '清理校园主干道垃圾，美化校园环境',
            'start_time' => now()->addDays(3),
            'end_time' => now()->addDays(3)->addHours(2), 
            'location' => '学校操场',
            'capacity' => 20,
            'status' => 'published', 
            'created_by' => $admin->id, 
        ]);
        Activity::create([
            'title' => 'activityForTest',
            'description' => 'No description',
            'start_time' => now(),
            'end_time' => now()->addDays(3)->addHours(2), 
            'location' => '1',
            'capacity' => 20,
            'status' => 'in_progress', 
            'created_by' => $admin->id, 
        ]);

        Activity::factory()
            ->count(50)
            ->create([
                'created_by' => $admin->id,
            ]);
    }
}