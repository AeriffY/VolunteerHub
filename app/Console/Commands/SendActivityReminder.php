<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ActivityNotification;

class SendActivityReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-activity-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $minutes = 10; // 提前10分钟提醒,可配置,启动:php artisan schedule:run
    
    $start = now()->addMinutes($minutes)->startOfMinute();
    $end = now()->addMinutes($minutes)->endOfMinute();
    

    $activities = Activity::where('status', 'published')
        ->whereBetween('start_time', [$start, $end])
        ->get();

    foreach ($activities as $activity) {
        $registrations = $activity->registrations()
            ->with('user')
            ->where('status', 'registered')
            ->get();

        if ($registrations->isEmpty()) continue;

        $users = $registrations->pluck('user');

        Notification::send(
            $users, 
            new ActivityNotification(
                $activity,
                "活动即将开始提醒",
                "您报名的活动《{$activity->title}》即将在 {$activity->start_time->format('H:i')} 开始，请提前做好准备。"
            )
        );
    }
}

}
