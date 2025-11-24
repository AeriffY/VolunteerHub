<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/signup', [UserController::class, 'sign_up']);
Route::middleware('auth')->group(function () {
    Route::post('/activities/{activity}/register', [RegistrationController::class, 'registerForActivity']);
    Route::delete('/activities/{activity}/register', [RegistrationController::class, 'cancelRegistration']);
});
