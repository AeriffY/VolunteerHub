<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ActivityController;


Route::redirect('/', '/login');

Route::get('/profile/export-pdf', function () {
    return '占位';
})->name('profile.exportPdf');


Route::view('/login', 'auth.login')->name('login'); 
Route::post('/login', [UserController::class, 'login']);
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

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
    Route::post('/admin/activities', [AdminController::class, 'storeActivity'])->name('admin.activities.store');
    Route::get('/admin/activities/{activity}/edit', [AdminController::class, 'editPage'])->name('admin.activities.edit');
    Route::put('/admin/activities/{activity}', [AdminController::class, 'updateActivity'])->name('admin.activities.update');
    Route::delete('/admin/activities/{activity}', [AdminController::class, 'cancelActivity'])->name('admin.activities.destroy');
    Route::get('/admin/activities', [AdminController::class, 'index'])->name('admin.activities.index');
    Route::get('/admin/activities/create', [AdminController::class, 'createActivityPage'])->name('admin.activities.create');
});


Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::post('/admin/activities/{activity}/generatecode', [CheckinController::class, 'generateCheckinCode'])->name('admin.activities.generatecode');
});
