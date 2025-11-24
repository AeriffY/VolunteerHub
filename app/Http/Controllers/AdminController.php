<?php

    namespace App\Http\Controllers;
    use App\Models\Activity;
    use Illuminate\Http\Request;

    class AdminController extends Controller {
        public function storeActivity(Request $request){
            $validated = $request->validate([
                'location'=>'required|string',
                'title'=>'required|string',
                'description'=>'required|string',

                //是否应该根据start_time和end_time来判断status是published还是in_progress?
                //fuck the database designer
                'status' => 'sometimes|in:published,cancelled,draft,in_progress,completed',
                'start_time'=>'required|date|after:now',
                'end_time'=>'required|date|after:start_time',
                'capacity'=>'required|integer|min:1'
            ]);

            $activity = Activity::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'capacity' => $validated['capacity'],
                'status' => $validated['status'],
                'created_by' => $request->user()->id,
            ]);

            return response()->json([
                'message'=>'publishActivitySuccess',
                'data'=>$activity
            ], 201);
        }

        public function updateActivity(Request $request, Activity $activity) {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'location' => 'sometimes|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
                'capacity' => 'sometimes|integer|min:1',

                //和上面一样的问题, fuck the database designer again
                'status' => 'sometimes|in:published,cancelled,draft,in_progress,completed' 
            ]);

            if(isset($validated['capacity'])) {
                $temp = $validated['capacity'];
                $current = $activity->registrations()->count();
                if($temp<$current) {
                    return response()->json([
                        'message'=>'givenDataInvalid',
                        'errors'=>[
                            'capacity' => [
                                "Capacity less than the current number of registered volunteers ({$current})."
                            ]
                        ]
                    ], 422);
                }
            }

            $newStart = isset($validated['start_time']) ? strtotime($validated['start_time']) : $activity->start_time->timestamp;
            $newEnd = isset($validated['end_time']) ? strtotime($validated['end_time']) : $activity->end_time->timestamp;
            if($newStart>=$newEnd) {
                return response()->json([
                    'message'=>'givenDataInvalid',
                    'errors' => [
                        'end_time' => ['start_time later than end_time']
                    ]
                ], 422);
            }

            $activity->update($validated);
            return response()->json([
                'message'=>'updateSuccess'
            ], 200);
        }

        public function cancelActivity(Activity $activity) {
            $activity->delete();
            return response()->json([
                'message' => 'cancelSuccess'
            ], 200);
        }
    }