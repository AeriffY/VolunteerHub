<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Activity;

    class ActivityController extends Controller {
        public function show(Request $request, Activity $activity){
            $user = $request->user();
            $registration = null;
            if($user) {
                $registration = $activity->registrations()->where('user_id', $user->id)->first();
            }

            $canCheckin = false;
            if ($registration && $activity->status === 'in_progress') {
            //&& $registration->status!=='checked_in'
                $canCheckin = true; 
            }

            return view('activities.show', compact('activity', 'registration', 'canCheckin'));
        }
    }