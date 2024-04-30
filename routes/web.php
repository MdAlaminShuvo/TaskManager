<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', 'App\Http\Controllers\TaskController@index');
Route::get('/tasks/create', 'App\Http\Controllers\TaskController@create');
Route::post('/tasks', 'App\Http\Controllers\TaskController@store');
Route::delete('/tasks/{id}', 'App\Http\Controllers\TaskController@destroy');
