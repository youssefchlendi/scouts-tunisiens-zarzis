<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\test;
use App\Http\Controllers\TroupeController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });
    Route::post('/sign-out', [AuthenticationController::class, 'logout']);
    Route::get('/test', [test::class, 'test']);

    Route::apiResource('leaders',LeaderController::class);
    Route::group(['prefix' => '/leaders'], function () {
    });
});

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::get('/unique/{id}', [AuthenticationController::class, 'unique']);

Route::group(['prefix' => '/roles'], function () {
    Route::get('/', [RoleController::class, 'index']);
});

Route::group(['prefix' => '/troupes'], function () {
    Route::get('/', [TroupeController::class, 'index']);
});
