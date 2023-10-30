<?php

use App\Models\Project;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate', function () {
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    Artisan::call('migrate:fresh --seed');
    return 'app cleared';
});
Route::get('/projects', function () {
    // $projects = Project::latest()->take(10)->get();

    // return response()->json([
    //     'state' => false,
    //     'data' => $projects,
    // ], 200);
    return "ay betngan";
});
