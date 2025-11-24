<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Checkin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckinController extends Controller
{
    //前往签到页（获取活动信息，用于签到）
    public function gotoCheckin($activity_id)
    {
        $activity = Activity::findOrFail($activity_id);

        if ($activity->status !== 'in_progress' && $activity->status !== 'published') {
            return response()->json([
                'message' => 'Activity cannot be checked in at this time.'
            ], 403);
        }

        return response()->json([
            'activity' => $activity,
        ]);
    }

    //储存签到结果
     
    public function storeCheckin(Request $request, $activity_id)
    {
        $activity = Activity::findOrFail($activity_id);

        if (!$activity->registrations()->where('user_id', Auth::id())->exists()) {
            return response()->json([
                'message' => 'You are not registered for this activity.'
            ], 403);
        }

        $validated = $request->validate([
            'checkin_code' => 'required|string',
        ]);

        if ($activity->checkin_code !== $validated['checkin_code']) {
            return response()->json([
                'message' => 'Invalid checkin code.'
            ], 422);
        }

        // 创建签到记录
        $checkin = Checkin::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'activity_id' => $activity->id,
            ],
            [
                'timestamp' => now(),
            ]
        );

        return response()->json([
            'message' => 'Checkin successful.',
            'checkin' => $checkin
        ]);
    }

    //生成签到码（仅管理员）
     
    public function generateCheckinCode($activity_id)
    {
        $activity = Activity::findOrFail($activity_id);

        $code = Str::random(6); // 生成6位随机签到码
        $activity->checkin_code = $code;
        $activity->save();

        return response()->json([
            'message' => 'Checkin code generated.',
            'checkin_code' => $code
        ]);
    }
}
