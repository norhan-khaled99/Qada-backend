<?php

use App\Http\Controllers\Office\AuthController;
use App\Http\Controllers\Office\CommentController;
use App\Http\Controllers\Office\ContractController;
use App\Http\Controllers\Office\OfferController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('otp/confirmation', [AuthController::class, 'otpConfirmation']);
Route::middleware('office')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});
Route::middleware('auth:sanctum','office', 'active')->group(function () {
    Route::post('/profile', function (Request $request) {
        return $request->user();
    });
    Route::post('/profile/update', [AuthController::class, 'updateUser']);
    Route::prefix('projects')->group(function () {
        Route::post('index', [ProjectController::class, 'index']);
        Route::post('create', [ProjectController::class, 'create']);
        Route::post('edit', [ProjectController::class, 'edit']);
        Route::post('newfile', [ProjectController::class, 'newfile']);
        Route::post('delete', [ProjectController::class, 'delete']);
    });
    Route::prefix('offers')->group(function () {
        Route::post('placeOffer', [OfferController::class, 'placeOffer']);
        Route::post('updateOffer', [OfferController::class, 'updateOffer']);
        Route::post('setstages', [OfferController::class, 'setstages']);
    });
    Route::prefix('contracts')->group(function () {
        Route::post('index', [ContractController::class, 'index']);
        Route::post('create', [ContractController::class, 'create']);
        Route::post('edit', [ContractController::class, 'edit']);
        Route::post('newfile', [ContractController::class, 'newfile']);
        Route::post('delete', [ContractController::class, 'delete']);
    });
    Route::prefix('stages')->group(function () {
        Route::post('submit', [StageController::class, 'submitstage']);
    });
    Route::prefix('comments')->group(function () {
        Route::post('create', [CommentController::class, 'index']);
        Route::post('edit', [CommentController::class, 'approveStage']);
        Route::post('delete', [CommentController::class, 'approveStage']);
    });
});
