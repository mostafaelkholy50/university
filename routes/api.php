<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControler;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\AuthControler;
use App\Http\Controllers\CommentNewsController;
use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Auth\AuthDoctorController;

Route::post('/register', [AuthControler::class, 'register']);
Route::post('/verify-code', [AuthControler::class, 'verifyCode']);
Route::post('/resend-code', [AuthControler::class, 'sendcode']);
Route::post('/login', [AuthControler::class, 'login']);
//-----------------------News-----------------------
Route::post('/news/search', [NewsController::class, 'search']);
Route::get('/news/{news}', [NewsController::class, 'show']);
Route::get('/news', [NewsController::class, 'index']);
//-----------------------Contact-----------------------
Route::post('/contact', [ContactController::class, 'store']);
//-----------------------Doctor-----------------------
Route::post('/doctor/register', [AuthDoctorController::class, 'register']);
Route::post('/doctor/login', [AuthDoctorController::class, 'login']);
Route::middleware('auth:doctor-api')->prefix('/doctor')->group(function () {
    Route::get('/', [DoctorController::class, 'show']);
    Route::post('/', [DoctorController::class, 'updateAuth']);
    Route::delete('/', [DoctorController::class, 'destroyAuth']);
});
//-------------------------User-------------------------

Route::middleware('auth:api')->prefix('/user')->group(function () {
    Route::get('/', [UserControler::class, 'showAuth']);
    Route::post('/comment', [CommentNewsController::class, 'store']);
    Route::post('/', [UserControler::class, 'updateAuth']);
    Route::delete('/', [UserControler::class, 'destroyAuth']);
    Route::get('/schedule', [ScheduleController::class, 'UserSchedule']);

});
//------------------------admin-----------------------
Route::post('/admin/login', [AuthAdminController::class, 'login']);
Route::post('/admin/logout', [AuthAdminController::class, 'logout']);
Route::post('/admin/register', [AuthAdminController::class, 'register']);

Route::middleware('auth:admin-api')->prefix('/admin')->group(function () {
    Route::get('/user', [UserControler::class, 'index']);
    Route::post('/user', [UserControler::class, 'store']);
    Route::get('/user/{id}', [UserControler::class, 'show']);
    Route::post('/user/{id}', [UserControler::class, 'update']);
    Route::delete('/user/{id}', [UserControler::class, 'destroy']);
    Route::get('/news', [NewsController::class, 'index']);
    Route::post('/Create-news', [NewsController::class, 'store']);
    Route::post('/news/search', [NewsController::class, 'search']);
    Route::get('/news/{news}', [NewsController::class, 'show']);
    Route::post('/news/{news}', [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);
    Route::get('/contact', [ContactController::class, 'index']);
    Route::get('/doctor', [DoctorController::class, 'index']);
    Route::get('/doctor/{doctor}', [DoctorController::class, 'showID']);
    Route::post('/doctor/{doctor}', [DoctorController::class, 'update']);
    Route::post('/doctor', [DoctorController::class, 'store']);
    Route::delete('/doctor/{doctor}', [DoctorController::class, 'destroy']);
    Route::get('/schedule', [ScheduleController::class, 'index']);
    Route::post('/schedule', [ScheduleController::class, 'store']);
    Route::post('/schedule/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy']);
});

