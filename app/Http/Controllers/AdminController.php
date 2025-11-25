<?php

    namespace App\Http\Controllers;
    use App\Models\Activity;
    use Illuminate\Http\Request;

    class AdminController extends Controller {
        public function storeActivity(Request $request){
            // dd('1');
            $validated = $request->validate([
                'location'=>'required|string',
                'title'=>'required|string',
                'description'=>'required|string',

                //是否应该根据start_time和end_time来判断status是published还是in_progress?
                //status只由管理员手动设置为draft、published或cancelled, ,in_progress,completed状态由系统自动更新
                'status' => 'sometimes|in:published,cancelled,draft',
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
            return redirect()->route('admin.activities.index');
        }

        public function updateActivity(Request $request, Activity $activity) {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'location' => 'sometimes|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date',
                'capacity' => 'sometimes|integer|min:1',
                //in_progress,completed状态由系统自动更新
                'status' => 'sometimes|in:published,cancelled,draft' 
            ]);

            if(isset($validated['capacity'])) {
                $temp = $validated['capacity'];
                $current = $activity->registrations()->count();
                if($temp<$current) {
                    $msg = "名额上限不能少于当前已报名人数 ({$current}人)。";
                    return back()->withErrors(['capacity' => $msg])->withInput();
                }
            }

            // 已开始的活动不能修改 start_time
            if (isset($validated['start_time']) && $activity->status === 'in_progress') {
                return back()->withErrors(['start_time'=>'Cannot modify start time after activity has started'])->withInput();
            }

            $newStart = isset($validated['start_time']) ? strtotime($validated['start_time']) : $activity->start_time->timestamp;
            $newEnd = isset($validated['end_time']) ? strtotime($validated['end_time']) : $activity->end_time->timestamp;
            if($newStart>=$newEnd) {
                $msg = "开始时间不能晚于结束时间。";
                return back()->withErrors(['end_time' => $msg, 'start_time' => " "])->withInput();
            }

            $activity->update($validated);

            $activity->updateStatus();

            return redirect()->route('admin.activities.index');
        }

        public function editPage(Activity $activity) {

            return view('admin.activities.edit', compact('activity'));
        }

        public function cancelActivity(Activity $activity) {
        // 活动已开始或完成不可直接删除?可选择设置 status 为 cancelled
        if ($activity->status === 'in_progress' || $activity->status === 'completed') {
            return back()->withErrors(['activity'=>'Cannot cancel activity that has started or completed']);
        }
            $activity->delete();
            return redirect()->route('admin.activities.index');
        }

        //管理员后台查看活动
        public function index(Request $request) {
            $query = Activity::query()->latest();
            if ($request->filled('search')) {
                $search = $request->input('search');

                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
                });
            }
            $activities = $query->paginate(10);
            $activities->appends($request->all());
            return view('admin.activities.index', compact('activities'));
        }

        public function createActivityPage(Request $request){
            return view('admin.activities.create');
        }
    }