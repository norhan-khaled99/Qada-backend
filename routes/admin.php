<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});





Route::middleware('auth:sanctum', 'admin','active')->group(function () {
    Route::post('/profile', function (Request $request) {
        return $request->user();
    });
    Route::post('/profile/update', [AuthController::class, 'updateUser']);
    Route::prefix('projects')->group(function () {
        Route::post('index', [ProjectController::class, 'index']);
        Route::post('approve', [ProjectController::class, 'approve']);
        Route::post('reject', [ProjectController::class, 'reject']);
    });
    Route::prefix('users')->group(function () {
        Route::post('index', [UserController::class, 'index']);
        Route::post('create', [UserController::class, 'create']);
        Route::post('delete', [UserController::class, 'delete']);
    });
});
