<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\AuthControler;

Route::post('/register',     [AuthControler::class, 'register']);
Route::post('/verify-code',  [AuthControler::class, 'verifyCode']);
Route::post('/resend-code',  [AuthControler::class, 'sendcode']);
Route::post('/login',        [AuthControler::class, 'login']);
//-----------------------News-----------------------
Route::get('/news', [NewsController::class, 'index']);
Route::post('/Create-news', [NewsController::class, 'store']);
Route::get('/news/{news}', [NewsController::class, 'show']);
Route::post('/news/{news}', [NewsController::class, 'update']);
Route::delete('/news/{news}', [NewsController::class, 'destroy']);
//----------------------contact---------------------
Route::post('/contact', [ContactController::class, 'store'])->middleware('auth:sanctum');
Route::get('/contact', [ContactController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
