<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $user = $request->user();
            $plainToken = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'message'=>'login success',
                'accessToken'=>$plainToken,
                'tokenType'=>'Bearer',
                'user'=>$user,
            ], 201);
        }
        
    }

    //logout
    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message'=>'logoutSuccess'
        ], 200);
    }
}
