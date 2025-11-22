<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hours;
use App\Models\User;

class HoursSeeder extends Seeder
{
    public function run(): void
    {
        // 为志愿者初始化时长（初始0小时）
        $volunteer = User::where('role', 'volunteer')->first();
        Hours::create([
            'user_id' => $volunteer->id,
            'total_hours' => 0.00,
        ]);
    }
}