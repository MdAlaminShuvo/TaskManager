<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Create API routes
Route::post('register', [authController::class, 'register']);
Route::post('login', [authController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('check', [authController::class, 'check']);
    Route::get('logout', [authController::class, 'logout']);
    Route::post('/tasks', 'App\Http\Controllers\TaskApiController@store');
    Route::put('/tasks/{id}', 'App\Http\Controllers\TaskApiController@update');
    Route::delete('/tasks/{id}', 'App\Http\Controllers\TaskApiController@destroy');
});

Route::get('/tasks', 'App\Http\Controllers\TaskApiController@index');

