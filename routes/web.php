<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;

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

Route::middleware(['auth', 'is_admin'])->group(function(){
    Route::post('/admin/activities', [AdminController::class, 'storeActivity']);
    Route::post('/admin/activities/{activity}', [AdminController::class, 'updateActivity']);
    Route::delete('/admin/activities/{activity}', [AdminController::class, 'cancelActivity']);
});

