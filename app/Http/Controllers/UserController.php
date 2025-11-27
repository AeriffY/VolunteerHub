<?php

namespace App\Http\Controllers;

use App\Models\Hours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Registration;
use App\Models\Checkin;

//Run: composer require barryvdh/laravel-dompdf
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    //login
    public function login(Request $request) {
        $valid = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $Authed = Auth::attempt($valid);
        if(!$Authed) {
            return back()->withErrors([
                'email' => 'Incorrect account or password',
            ])->withInput($request->except('password'));
        } else {
            $request->session()->regenerate();

            $user = Auth::user();
            if($user->role === 'admin') {
                return redirect()->route('admin.activities.index');
            }
            return redirect()->route('activities.index');
        }
        
    }

    //logout
    public function logout(Request $request){
        // $user = $request->user();
        // $user->currentAccessToken()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function sign_up(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'volunteer', //default:volunteer ??
        ]);
        $user->hours()->create(['total_hours'=>0]);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('login');
    }

    public function viewProfile(Request $request) {
        $user = $request->user();
        // $registrations = Registration::with('activity')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $registrations = Registration::with(['activity'=>function($query) use ($user) {
            $query->with(['reviews'=>function($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);
        }])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        $hours = $user->hours;
        return view('profile.show', compact('user', 'registrations', 'hours'));
    }

    public function exportPDF(Request $request)
    {
        $user = $request->user();

        $CheckedActivity = Checkin::where('user_id', $user->id)->pluck('activity_id');
        $completedRegistrations = Registration::with('activity')->where('user_id', $user->id)
        ->whereIn('activity_id', $CheckedActivity)->orderBy('created_at', 'desc')->get();

        $totalHours = Hours::where('user_id', $user->id)->value('total_hours');

        $pdf = Pdf::loadView('pdf.Certificate', [
            'user' => $user,
            'registrations' => $completedRegistrations,
            'totalHours' => $totalHours,
            'date' => now()->format('Y-m-d'),
        ]);

        return $pdf->download("志愿服务证明_{$user->name}.pdf");
    }
}
