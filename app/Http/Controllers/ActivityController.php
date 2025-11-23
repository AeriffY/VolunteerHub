<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    //
    public function registerForActivity(Request $request, Activity $activity){
        $user = $request->user();
        if($activity->status!=='in_progress') {
            return response()->json([
                'message'=>'Registration failed, as this activity is not currently active'
            ], 400);
        }

        //Not user-friendly enough, I'll update the logic later
        $hasRegistered = Registration::where('user_id', $user->id)->where('activity_id', $activity->id)->exists();
        if($hasRegistered) {
            return response()->json([
                'message'=>'Registration failed, as you\'ve already registered for this activity'
            ], 409);
        }

        $trans = DB::transaction(
            function() use ($activity, $user) {
                $lock = Activity::where('id', $activity->id)->lockForUpdate();
                $locked = $lock->first();
                if($locked->registrations()->count()>=$locked->capacity) {
                    return response()->json([
                        'message'=>'Registration failed, as this activity is full'
                    ], 400);
                }
                Registration::create([
                    'user_id'=>$user->id,
                    'activity_id'=>$activity->id,
                    'status'=>'registered',
                    'registration_time'=>now(),
                ]);
                return response()->json([
                    'message'=>'Registration success'
                ], 201);
            }
        );
    }
}
