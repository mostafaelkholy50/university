<?php

use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Auth\AuthDoctorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserControler;
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
Route::post('/news/search', [NewsController::class, 'search']);
Route::get('/news/{news}', [NewsController::class, 'show']);
Route::post('/news/{news}', [NewsController::class, 'update']);
Route::delete('/news/{news}', [NewsController::class, 'destroy']);
//----------------------contact---------------------
Route::post('/contact', [ContactController::class, 'store'])->middleware('auth:api');
Route::get('/contact', [ContactController::class, 'index'])->middleware('auth:sanctum');
//-----------------------Doctor-----------------------
Route::post('/doctor/register', [AuthDoctorController::class, 'register']);
Route::post('/doctor/login', [AuthDoctorController::class, 'login']);
Route::middleware('auth:doctor-api')->group(function () {
    Route::get('/doctor', [DoctorController::class, 'show']);
});
Route::post('/doctor', [DoctorController::class, 'store']);
Route::post('/doctor/{doctor}', [DoctorController::class, 'update']);
Route::delete('/doctor/{doctor}', [DoctorController::class, 'destroy']);
//--------------------------------------------------
Route::get('/user',[UserControler::class, 'index']);
Route::post('/user',[UserControler::class, 'store']);
Route::get('/user/{id}',[UserControler::class, 'show']);
Route::post('/user/{id}',[UserControler::class, 'update']);
Route::delete('/user/{id}',[UserControler::class, 'destroy']);
//------------------------admin-----------------------
Route::post('/admin/login', [AuthAdminController::class, 'login']);
Route::post('/admin/logout', [AuthAdminController::class, 'logout']);
Route::post('/admin/register', [AuthAdminController::class, 'register']);


