<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // 删除原有time字段
            $table->dropColumn('time');
            // 添加开始时间和结束时间字段（均为datetime类型）
            $table->datetime('start_time'); // 活动开始时间
            $table->datetime('end_time');   // 活动结束时间
        });

        DB::statement("
        ALTER TABLE activities 
        MODIFY COLUMN status ENUM('published', 'cancelled', 'draft', 'in_progress', 'completed') 
        DEFAULT 'draft'
    ");
    }

    
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
           
            $table->datetime('time');
            $table->dropColumn(['start_time', 'end_time']);
        });

        DB::statement("
        ALTER TABLE activities 
        MODIFY COLUMN status ENUM('published', 'cancelled', 'draft') 
        DEFAULT 'draft'
    ");
    }
};