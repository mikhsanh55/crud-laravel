<?php

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

// 
Route::get("/user", [
    \App\Http\Controllers\Controller::class, 'index'
]);

Route::get('/add-user', [
    \App\Http\Controllers\Controller::class, 'add'
]);
Route::post('/store', [
    \App\Http\Controllers\Controller::class, 'store'
]);

Route::get('/edit-user', [
    \App\Http\Controllers\Controller::class, 'edit'
]);

Route::post('/update', [
    \App\Http\Controllers\Controller::class, 'update'
]);

Route::get('/delete-user', [
    \App\Http\Controllers\Controller::class, 'delete'
]);