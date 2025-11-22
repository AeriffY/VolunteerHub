<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // 关联报名用户
            $table->foreignId('activity_id')->constrained(); // 关联活动
            $table->datetime('registration_time');
            $table->enum('status', ['registered', 'cancelled'])->default('registered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};