<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    //
    public function registerForActivity(Request $request, Activity $activity){
        $user = $request->user();
        if($activity->status!=='published') {
            $msg = '报名失败：活动不在可报名时间段内或已结束';
            return back()->with('error', $msg);
        }

        $hasRegistered = Registration::where('user_id', $user->id)->where('activity_id', $activity->id)->exists();
        if($hasRegistered) {
            return back()->with('error', '您已经报名过了');
        }

        $trans = DB::transaction(
            function() use ($activity, $user) {
                $lock = Activity::where('id', $activity->id)->lockForUpdate();
                $locked = $lock->first();
                if($locked->registrations()->count()>=$locked->capacity) {
                    return back()->with('error', '活动名额已满');
                }
                Registration::create([
                    'user_id'=>$user->id,
                    'activity_id'=>$activity->id,
                    'status'=>'registered',
                    'registration_time'=>now(),
                ]);
                // return redirect()->route('activities.show', $activity->id);
                return back()->with('success', '报名成功');
            }
        );
        return $trans;
    }

    public function cancelRegistration(Request $request, Registration $registration) {
        $user = $request->user();
        if ($registration->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $activity = $registration->activity;
        //除了published阶段都无法取消
        if($activity->status === 'in_progress') {
            return back()->with('error', '活动已开始, 无法取消报名');
        }
        if($activity->status === 'cancelled') {
            return back()->with('error', '活动已取消');
        }
        if($activity->status === 'completed') {
            return back()->with('error', '活动已结束');
        }
        
        //虽然表里有一个status的enum字段, 有registered和cancelled两个值, 但是我直接删记录了
        $registration->delete();
        return back()->with('success', '取消报名成功');
    }
}
