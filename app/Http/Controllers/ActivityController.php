<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;


class ActivityController extends Controller{
    //for all roles
    //show all activities & search
    public function index(Request $request){
        $query = Activity::with('creator')->where('status', 'published');

        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search){
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
    
        $activities = $query->paginate(10);

        return response()->json($activities);
    }
    //for single activity details
    public function show($id){
        return Activity::with('creator')->findOrFail($id);
    }
    //for admin
    //create new activity
    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:2',
            'status' => 'required|string',
        ]
        );
        $validated['created_by'] = Auth::id();
        $activity = Activity::create($validated);
        return response()->json(['message'=>'Activity created', 'activity'=>$activity], 201);

    }
    //edit activity
    public function update(Request $request, $id){
        $activity = Activity::findOrFail($id);
        //status:'published' / 'cancelled' / 'draft' / 'in_progress'/ 'completed'
        if($activity->status == 'cancelled'|| $activity->status == 'completed' ||  $activity->end_time < now()){
            return response()->json(['message'=>'Cannot modify ended activity'], 403);
        }

        $activity->update($request->only([
            'title', 'description', 'start_time', 'end_time',
            'location', 'capacity', 'status'
        ]));

        return response()->json(['message'=>'Activity updated', 'activity'=>$activity]);

    }
    //delete activity
    public function destroy(Request $request, $id){
        $activity = Activity::findOrFail($id);

        if($activity->status == 'cancelled'|| $activity->status == 'completed' ||  $activity->end_time < now()){
            return response()->json(['message'=>'Cannot delete ended activity'], 403);
        }
        $activity->delete();

        return response()->json(['message'=>'Activity deleted']);
    }


}
?>