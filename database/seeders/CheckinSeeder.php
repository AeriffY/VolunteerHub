<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Checkin;
use App\Models\User;
use App\Models\Activity;
use Carbon\Carbon;

class CheckinSeeder extends Seeder
{
    public function run(): void
    {
        $targetId = 3;
        $volunteer = User::find($targetId);

        if (!$volunteer) {
            $this->command->error("找不到");
            return;
        }

        $activities = Activity::all();

        if ($activities->isEmpty()) {
            $this->command->info('空的');
            $activities = Activity::factory()->count(20)->create();
        }

        $count = 0;
        foreach ($activities as $activity) {
            
            $exists = Checkin::where('activity_id', $activity->id)
                             ->where('user_id', $targetId)
                             ->exists();
            
            if ($exists) {
                continue;
            }

            $checkinTime = Carbon::parse($activity->start_time)->addMinutes(rand(-30, 10));

            Checkin::create([
                'activity_id' => $activity->id,
                'user_id'     => $targetId,
                'timestamp'   => $checkinTime,
            ]);

            $count++;
        }

        $this->command->info("Success");
    }
}