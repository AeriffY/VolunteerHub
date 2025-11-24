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
            return response()->json([
                'message'=>'unableToRegister'
            ], 400);
        }

        //Not user-friendly enough, I'll update the logic later
        $hasRegistered = Registration::where('user_id', $user->id)->where('activity_id', $activity->id)->exists();
        if($hasRegistered) {
            return response()->json([
                'message'=>'alreadyRegistered'
            ], 409);
        }

        $trans = DB::transaction(
            function() use ($activity, $user) {
                $lock = Activity::where('id', $activity->id)->lockForUpdate();
                $locked = $lock->first();
                if($locked->registrations()->count()>=$locked->capacity) {
                    return response()->json([
                        'message'=>'fullActivity'
                    ], 400);
                }
                Registration::create([
                    'user_id'=>$user->id,
                    'activity_id'=>$activity->id,
                    'status'=>'registered',
                    'registration_time'=>now(),
                ]);
                return redirect()->route('activities.show', $activity->id);
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
        if($activity->status !== 'published') {
            return back()->with('error', 'can not cancel registration');
        }
        
        //虽然表里有一个status的enum字段, 有registered和cancelled两个值, 但是我直接删记录了
        $registration->delete();
        return back()->with('success', 'Registration cancelled successfully');
    }
}
