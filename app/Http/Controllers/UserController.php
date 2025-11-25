<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Registration;

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
            return response()->json(['message'=>'IncorrectAccountOrPassword'], 401);
        } else {
            $request->session()->regenerate();
            // return response()->json([
            //     'message'=>'login success',
            //     'user'=>Auth::user(),
            // ], 200);
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
        // return response()->json([
        //     'message'=>'logoutSuccess'
        // ], 200);
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
        // return response()->json([
        //     'message'=>'signUpSuccess'
        // ], 201);
        return redirect()->route('login');
    }

    public function viewProfile(Request $request) {
        $user = $request->user();
        $registrations = Registration::with('activity')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $hours = $user->hours;
        return view('profile.show', compact('user', 'registrations', 'hours'));
    }
}
