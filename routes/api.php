<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;


// Route::post('/login', [UserController::class, 'login']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//Maybe display activities even when not logged in?


// //Sanctum
// Route::middleware('auth:sanctum')->group(function () {

//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
  
//     Route::post('/logout', [UserController::class, 'logout']);
    
//     Route::post('/activities/{activity}/register', [RegistrationController::class, 'registerForActivity']);
    
// });