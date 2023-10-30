<?php

use App\Http\Controllers\Bussiness\AuthController;
use App\Http\Controllers\Bussiness\CommentController;
use App\Http\Controllers\Bussiness\ContractController;
use App\Http\Controllers\Bussiness\ProjectController;
use App\Http\Controllers\Bussiness\StageController;
use App\Models\Offer;
use App\Models\Project;
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
//Route::post('otp/confirmation', [AuthController::class, 'otpConfirmation']);
Route::middleware('bussiness')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('otp/confirmation', [AuthController::class, 'otpConfirmation']);



Route::middleware('auth:sanctum','bussiness', 'active')->group(function () {
    Route::post('/profile',function(Request $request){
        return $request->user();
    });
    Route::post('/profile/update',[AuthController::class,'updateUser']);
    Route::prefix('projects')->group(function () {
        Route::post('index',[ProjectController::class,'index']);
        Route::post('create',[ProjectController::class,'create']);
        Route::post('edit',[ProjectController::class,'edit']);
        Route::post('newfile',[ProjectController::class, 'newfile']);
        Route::post('delete',[ProjectController::class,'delete']);
    });
    Route::prefix('contracts')->group(function () {
        Route::post('index', [ContractController::class, 'index']);
        Route::post('create', [ContractController::class, 'create']);
        Route::post('edit', [ContractController::class, 'edit']);
        Route::post('newfile', [ContractController::class, 'newfile']);
        Route::post('delete', [ContractController::class, 'delete']);
    });
    Route::prefix('stages')->group(function () {
        Route::post('index', [StageController::class, 'index']);
        Route::post('approvestage', [StageController::class, 'approveStage']);
    });
    Route::prefix('comments')->group(function () {
        Route::post('create', [CommentController::class, 'create']);
        Route::post('edit', [CommentController::class, 'edit']);
        Route::post('delete', [CommentController::class, 'delete']);
    });
    Route::post('request/discount', function (Request $request) {
        $project = Project::find($request->project_id);
        if ($project) {
            $count_offers = Offer::where('project_id', $project->id)->where('discount_requested', 1)->count();
            if ($count_offers >= 3) {
                $offer = Offer::find($request->offer_id);
                $offer->update([
                    'discount_requested' => 1
                ]);
                return response()->json([
                    'state' => true,
                ], 200);
            } else {
                return response()->json([
                    'state' => false,
                ], 302);
            }
        } else {
            return response()->json([
                'state' => false,
                'data' => 'Undefined Project',
            ], 302);
        }
    })->name('request.discount');
});
