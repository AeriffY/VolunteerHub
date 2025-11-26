<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Activity;
    use App\Models\Checkin;
use Illuminate\Notifications\Action;

    class ActivityController extends Controller {
        public function show(Request $request, Activity $activity){
            $activity->updateStatus(); // 每次访问前更新状态
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

            $hasCheckedIn = Checkin::where('user_id', $user->id)->where('activity_id', $activity->id)->exists();

            return view('activities.show', compact('activity', 'registration', 'canCheckin', 'hasCheckedIn'));
        }

        public function index(Request $request, Activity $activity) {
            $activity->updateAllStatus();
            $query = Activity::whereNotIn('status', ['draft', 'cancelled']);
            if($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
                });
            }

            $activities = $query->latest()->paginate(10);
            $activities->appends($request->all());

            // foreach($activities as $activity){
            //     $activity->updateStatus();
            // }

            return view('activities.index', compact('activities'));
        }
    }