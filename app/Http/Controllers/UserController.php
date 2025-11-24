<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            return response()->json([
                'message'=>'login success',
                'user'=>Auth::user(),
            ], 200);
        }
        
    }

    //logout
    public function logout(Request $request){
        // $user = $request->user();
        // $user->currentAccessToken()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'message'=>'logoutSuccess'
        ], 200);
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
        return response()->json([
            'message'=>'signUpSuccess'
        ], 201);
    }
}
