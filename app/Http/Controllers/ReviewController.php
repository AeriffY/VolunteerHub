<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

use App\Models\Review;
use App\Models\Checkin;

class ReviewController extends Controller
{
    //
    public function store(Request $request, Activity $activity){
        $user = $request->user();
        if ($activity->status!=='completed') {
            $msg = '活动未结束, 暂时无法回顾';
            return back()->with('error', $msg);
        }
        $hasCheckedIn = Checkin::where('user_id', $user->id)->where('activity_id', $activity->id)->exists();
        if(!$hasCheckedIn) {
            return back()->with('error', '您未签到此活动, 无法发表回顾');
        }
        $validated = $request->validate([
            'review_title' => 'required|string|max:255',
            'content'=>'required|string|min:5|max:1000',
            'images.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images'       => 'max:5',
        ]);
        $imagePaths = [];
        if($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('reviews', 'public');
                $imagePaths[] = $path;
            }
        }
        Review::create([
            'user_id'     => $user->id,
            'activity_id' => $activity->id,
            'title'       => $validated['review_title'],
            'content'     => $validated['content'],
            'image_paths'  => $imagePaths,
        ]);
        // return back()->with('success', '您的活动回顾已发布');
        return redirect()->route('profile.show')->with('success', '感谢您的分享, 您的活动回顾已成功发布');
    }
    public function create(Request $request, Activity $activity){
        return view('review.upload', compact('activity'));
    }
    public function show(Review $review) {
        $review->load(['user', 'activity']);
        $activity = $review->activity;
        return view('review.show', compact('review', 'activity'));
    }
}
