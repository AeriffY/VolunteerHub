<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ActivityController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/login');
Route::get('/activities', function () {
    return '占位';
})->name('activities.index');
Route::get('/profile/export-pdf', function () {
    return '占位';
})->name('profile.exportPdf');


Route::view('/login', 'auth.login')->name('login'); 
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [UserController::class, 'sign_up'])->name('register');
Route::middleware('auth')->group(function () {
    Route::post('/registrations/{activity}', [RegistrationController::class, 'registerForActivity'])->name('registrations.store');
    Route::delete('/registrations/{registration}', [RegistrationController::class, 'cancelRegistration'])->name('registrations.destroy');
    Route::get('/profile', [UserController::class, 'viewProfile'])->name('profile.show');

    Route::get('/checkin/{activity}/create', [CheckinController::class, 'gotoCheckin'])->name('checkin.create');
    Route::post('/checkin/{activity}', [CheckinController::class, 'storeCheckin'])->name('checkin.store');

    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
});

Route::middleware(['auth', 'is_admin'])->group(function(){
    Route::post('/admin/activities', [AdminController::class, 'storeActivity']);
    Route::post('/admin/activities/{activity}', [AdminController::class, 'updateActivity']);
    Route::delete('/admin/activities/{activity}', [AdminController::class, 'cancelActivity']);
});


Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::post('/admin/activities/{activity}/generatecode', [CheckinController::class, 'generateCheckinCode'])->name('admin.activities.generatecode');
});
