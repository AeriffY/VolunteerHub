<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;

//login
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // 获取当前登录用户的信息 (测试 Token 是否有效常用)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- 您的业务逻辑 ---

    // 活动报名路由
    // {activity} 是一个占位符，代表活动 ID (例如 /activities/1/attend)
    // ActivityController 的 attend 方法会处理这个请求
    Route::post('/activities/{activity}/attend', [ActivityController::class, 'attend']);

    // 将来您的签到路由也可以写在这里
    // Route::post('/activities/{activity}/checkin', [ActivityController::class, 'checkIn']);
});