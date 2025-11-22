<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 严格按顺序执行，确保关联正确
        $this->call([
            UserSeeder::class,       // 先创建用户
            ActivitySeeder::class,   // 再创建活动（依赖管理员）
            RegistrationSeeder::class, // 再创建报名（依赖用户和活动）
            CheckinSeeder::class,    // 再创建签到（依赖用户和活动）
            HoursSeeder::class,      // 最后初始化时长（依赖志愿者）
        ]);
    }
}