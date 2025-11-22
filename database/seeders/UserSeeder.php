<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 创建管理员
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'), // 简单密码
            'role' => 'admin',
        ]);

        // 2. 创建志愿者
        User::create([
            'name' => 'Volunteer User',
            'email' => 'volunteer@test.com',
            'password' => Hash::make('123456'),
            'role' => 'volunteer',
        ]);
    }
}