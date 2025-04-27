<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControler;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\Auth\AuthControler;
use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\CommentNewsController;
use App\Http\Controllers\CommentCourseController;
use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Auth\AuthDoctorController;
use App\Http\Controllers\TermOnePaymentsController;
use App\Http\Controllers\TermTwoPaymentsController;

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
//-----------------------Courses-----------------------
Route::get('/courses', [CoursesController::class, 'index']);
Route::get('/courses/{courses}', [CoursesController::class, 'show']);
//-----------------------lectures-----------------------
Route::get('/lectures', [LectureController::class, 'index']);
Route::get('/lectures/{lectures}', [LectureController::class, 'showID']);
//-----------------------Doctor-----------------------
Route::post('/doctor/register', [AuthDoctorController::class, 'register']);
Route::post('/doctor/login', [AuthDoctorController::class, 'login']);
Route::middleware('auth:doctor-api')->prefix('/doctor')->group(function () {
    Route::get('/', [DoctorController::class, 'show']);
    Route::post('/', [DoctorController::class, 'updateAuth']);
    Route::delete('/', [DoctorController::class, 'destroyAuth']);
    //-----------------------Courses-----------------------
    Route::get('/courses', [CoursesController::class, 'index']);
    Route::get('/courses/{courses}', [CoursesController::class, 'show']);
    Route::post('/courses', [CoursesController::class, 'store']);
    Route::post('/courses/{courses}', [CoursesController::class, 'update']);
    Route::delete('/courses/{courses}', [CoursesController::class, 'destroy']);
    //-----------------------episodes-----------------------
    Route::get('/episodes', [EpisodesController::class, 'index']);
    Route::get('/episodes/{episodes}', [EpisodesController::class, 'showid']);
    Route::post('/episodes', [EpisodesController::class, 'store']);
    Route::post('/episodes/{episodes}', [EpisodesController::class, 'update']);
    Route::delete('/episodes/{episodes}', [EpisodesController::class, 'destroy']);
    //-----------------------Lectures-----------------------
    Route::get('/lectures', [LectureController::class,'showDoctor']);
    Route::get('/lectures/{lecture}', [LectureController::class, 'showID']);
    Route::post('/lectures', [LectureController::class, 'store']);
    Route::post('/lectures/{lecture}', [LectureController::class, 'update']);
    Route::delete('/lectures/{lecture}', [LectureController::class, 'destroy']);
});
//-------------------------User-------------------------

Route::middleware('auth:api')->prefix('/user')->group(function () {
    Route::get('/', [UserControler::class, 'showAuth']);
    Route::post('/comment', [CommentNewsController::class, 'store']);
    Route::post('/', [UserControler::class, 'updateAuth']);
    Route::delete('/', [UserControler::class, 'destroyAuth']);
    Route::get('/schedule', [ScheduleController::class, 'UserSchedule']);
    Route::get('/grades/one', [GradesController::class, 'ShowGradesAuth']);
    Route::get('/grades/Two', [GradesController::class, 'ShowGradesTwoAuth']);
    //-----------------------courses-----------------------
    Route::get('/courses', [CoursesController::class, 'index']);
    Route::get('/courses/{courses}', [CoursesController::class, 'show']);
    Route::get('/episodes/{episodes}', [episodesController::class, 'show']);
    //-----------------------Enroll-----------------------
    Route::get('/enroll', [EnrollController::class, 'show']);
    Route::post('/enroll', [EnrollController::class, 'store']);
    Route::delete('/enroll/{enroll}', [EnrollController::class, 'destroy']);
    //-----------------------lectures-----------------------
    Route::get('/lectures', [LectureController::class, 'show']);
    Route::get('/lectures/{lecture}', [LectureController::class, 'showID']);
        //-----------------------Comments-----------------------
        Route::post('/courses/comments', [CommentCourseController::class, 'store']);
});
//------------------------admin-----------------------
Route::post('/admin/login', [AuthAdminController::class, 'login']);
Route::post('/admin/logout', [AuthAdminController::class, 'logout']);
Route::post('/admin/register', [AuthAdminController::class, 'register']);

Route::middleware('auth:admin-api')->prefix('/admin')->group(function () {
    //-----------------------User-----------------------
    Route::get('/user', [UserControler::class, 'index']);
    Route::post('/user', [UserControler::class, 'store']);
    Route::get('/user/{id}', [UserControler::class, 'show']);
    Route::post('/user/{id}', [UserControler::class, 'update']);
    Route::delete('/user/{id}', [UserControler::class, 'destroy']);
    //-----------------------News-----------------------
    Route::get('/news', [NewsController::class, 'index']);
    Route::post('/Create-news', [NewsController::class, 'store']);
    Route::post('/news/search', [NewsController::class, 'search']);
    Route::get('/news/{news}', [NewsController::class, 'show']);
    Route::post('/news/{news}', [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);
    //-----------------------contact-----------------------
    Route::get('/contact', [ContactController::class, 'index']);
    //------------------------Doctor-----------------------
    Route::get('/doctor', [DoctorController::class, 'index']);
    Route::get('/doctor/{doctor}', [DoctorController::class, 'showID']);
    Route::post('/doctor/{doctor}', [DoctorController::class, 'update']);
    Route::post('/doctor', [DoctorController::class, 'store']);
    Route::delete('/doctor/{doctor}', [DoctorController::class, 'destroy']);
    //-----------------------Schedule-----------------------
    Route::get('/schedule', [ScheduleController::class, 'index']);
    Route::post('/schedule', [ScheduleController::class, 'store']);
    Route::post('/schedule/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy']);
    //-----------------------Subjects-----------------------
    Route::post('/subjects', [SubjectsController::class, 'store']);
    Route::get('/subjects', [SubjectsController::class, 'index']);
    Route::get('/subjects/{subjects}', [SubjectsController::class, 'show']);
    Route::post('/subjects/{subjects}', [SubjectsController::class, 'update']);
    Route::delete('/subjects/{subjects}', [SubjectsController::class, 'destroy']);
    //-----------------------Grades-----------------------
    Route::post('/grades', [GradesController::class, 'store']);
    Route::get('/grades/{grades}', [GradesController::class, 'show']);
    Route::post('/grades/{grades}', [GradesController::class, 'update']);
    Route::delete('/grades/{grades}', [GradesController::class, 'destroy']);
    //-----------------------Term Payments-----------------------
    Route::post('/term_one_payments', [TermOnePaymentsController::class, 'store']);
    Route::delete('/term_one_payments/{id}', [TermOnePaymentsController::class, 'destroy']);
    Route::delete('/term_one_payments/all', [TermOnePaymentsController::class, 'deleteAll']);
    Route::post('/term_two_payments', [TermTwoPaymentsController::class, 'store']);
    Route::delete('/term_two_payments/{id}', [TermTwoPaymentsController::class, 'destroy']);
    Route::delete('/term_two_payments/all', [TermTwoPaymentsController::class, 'deleteAll']);
    //-----------------------enroll-----------------------
    Route::get('/enroll', [EnrollController::class, 'index']);
    Route::get('/enroll/{enroll}', [EnrollController::class, 'showid']);
    Route::post('/enroll/{enroll}', [EnrollController::class, 'update']);
    Route::delete('/enroll/{enroll}', [EnrollController::class, 'destroy']);

});

