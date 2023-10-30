<?php

use App\Http\Controllers\Person\AuthController;
use App\Http\Controllers\Person\CommentController;
use App\Http\Controllers\Person\OfferController;
use App\Http\Controllers\Person\ProjectController;
use App\Http\Controllers\Person\StageController;
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
Route::post('otp/confirmation', [AuthController::class, 'otpConfirmation']);
Route::middleware('person')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});
Route::middleware('auth:sanctum', 'person', 'active')->group(function () {
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
        Route::post('create', [CommentController::class, 'create']);
        Route::post('edit', [CommentController::class, 'edit']);
        Route::post('delete', [CommentController::class, 'delete']);
    });
    Route::post('request/discount',function(Request $request){
        $project = Project::find($request->project_id);
        if($project){
            $count_offers = Offer::where('project_id',$project->id)->where('discount_requested',1)->count();
            if($count_offers >= 3){
                $offer = Offer::find($request->offer_id);
                $offer->update([
                    'discount_requested'=>1
                ]);
                return response()->json([
                    'state' => true,
                ], 200);
            }else{
                return response()->json([
                    'state' => false,
                ], 302);
            }
        }else{
            return response()->json([
                'state' => false,
                'data' => 'Undefined Project',
            ], 302);
        }
    })->name('person.request.discount');
});
