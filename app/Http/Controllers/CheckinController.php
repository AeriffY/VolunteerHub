<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Checkin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    //前往签到页（获取活动信息，用于签到）
    public function gotoCheckin($activity_id)
    {
        $activity = Activity::findOrFail($activity_id);

        if ($activity->status !== 'in_progress') {
            return response()->json([
                'message' => 'Activity cannot be checked in at this time.'
            ], 403);
        }

        return view('checkin.create', compact('activity'));
    }

    //储存签到结果
     
    public function storeCheckin(Request $request, $activity_id)
    {
        $activity = Activity::findOrFail($activity_id);
        $user = Auth::user();

        if (!$activity->registrations()->where('user_id', Auth::id())->exists()) {
            return response()->json([
                'message' => 'You are not registered for this activity.'
            ], 403);
        }

        $validated = $request->validate([
            'checkin_code' => 'required|string',
        ]);

        if ($activity->checkin_code !== $validated['checkin_code']) {
            $msg = "签到码错误，请重试。";
            return back()->withErrors(['checkin_code' => $msg])->withInput();
        }

        $hasCheckedIn = Checkin::where('user_id', $user->id)->where('activity_id', $activity->id)->exists();
        if($hasCheckedIn) {
            return response()->json(['message' => 'alreadyCheckedIn'], 409);
        }

        return DB::transaction(function() use($user, $activity) {
            $checkin = Checkin::create([
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'timestamp'=>now(),
                ]);

            $duration = $activity->end_time->floatDiffInHours($activity->start_time);
            $user->hours()->firstOrCreate([], ['total_hours'=>0])->increment('total_hours', $duration);
            return redirect()->route('activities.show', $activity->id)
                         ->with('success', '签到成功！已为您增加 '.$duration.' 小时志愿时长。');
        });
    }

    //生成签到码（仅管理员）
     
    public function generateCheckinCode($activity_id)
    {
        $activity = Activity::findOrFail($activity_id);

        $code = Str::random(6); // 生成6位随机签到码
        $activity->checkin_code = $code;
        $activity->save();

        $message = "签到码生成成功：{$code}";
        return back()->with('success', $message);
    }
}
