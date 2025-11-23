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
        $user = request()->user();
        if($activity->status!=='recruiting') {
            
        }
    }
}
