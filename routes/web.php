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

Route::group(['middleware' => ['auth:sanctum', 'verified']],function (){
    Route::get("/dashboard",[\App\Http\Controllers\DashboardController::class,'dashboard'])->name('dashboard');
    Route::get("/send/message",[\App\Http\Controllers\MessageController::class,'sendMessage'])->name('send.message');


//    Route::get('/users','DashboardController@dashboard')->name('users');
    Route::get('/send', function () {
    })->name('send');
});
