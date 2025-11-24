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
                return response()->json([
                    'message'=>'Registration success'
                ], 201);
            }
        );
        return $trans;
    }

    public function cancelRegistration(Request $request, Activity $activity) {
        $user = $request->user();
        $record = Registration::where('user_id', $user->id)->where('activity_id', $activity->id)->first();
        if(!$record) {
            return response()->json([
                'message'=>'notRegistered'
            ], 404);

        }

        //开始了不让取消
        if($activity->status === 'in_progress') {
            return response()->json([
                'message'=>'hasStarted'
            ], 403);
        }
        
        $record->delete();
        return response()->json([
            'message'=>'cancelSuccess'
        ],200);
    }
}
