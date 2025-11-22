<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->datetime('time');
            $table->string('location');
            $table->integer('capacity'); // 人数限制
            $table->enum('status', ['published', 'cancelled', 'draft'])->default('draft');
            $table->foreignId('created_by')->constrained('users'); // 关联创建活动的管理员
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};