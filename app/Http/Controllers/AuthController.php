<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request) {
        $valid = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $Authed = Auth::attempt($valid);
        if(!$Authed) {
            return response()->json(['message'=>'Incorrect account or password.'], 401);
        } else {
            $user = $request->user();
            $plainToken = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'message'=>'login success',
                'accessToken'=>$plainToken,
                'tokenType'=>'Bearer',
                'user'=>$user,
            ]);
        }
        
    }
}
