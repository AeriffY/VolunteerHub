<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;


Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Maybe display activities even when not logged in?


//Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
  
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/activities/{activity}/attend', [ActivityController::class, 'registerForActivity']);
    
});