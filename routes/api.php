<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Category\CategoryController;
use App\Http\Controllers\V1\ForgotPassword\ForgotPasswordController;
use App\Http\Controllers\V1\Group\GroupController;
use App\Http\Controllers\V1\Note\NoteController;
use App\Http\Controllers\V1\Profile\ProfileController;
use App\Http\Controllers\V1\SubTask\SubtaskController;
use App\Http\Controllers\V1\Task\TaskController;
use App\Http\Controllers\V1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user/')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify/code' , [AuthController::class, 'check_verify_code']);
    Route::post('/forgot/password' , [ForgotPasswordController::class, 'forgotpassword']);
    Route::post('/verify/otp' , [ForgotPasswordController::class, 'check_otp_code']);
    Route::post('/change/password' , [ForgotPasswordController::class, 'ChangePassword']);
})->middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('profile/show', [ProfileController::class, 'show']);
    Route::post('profile/update/{user}', [ProfileController::class, 'update']);
    Route::patch('change/password', [ProfileController::class, 'changePassword']);
    Route::get('/search/user', [UserController::class , 'user_list']);

});


Route::prefix('v1/task/')->middleware('auth:api')->group(function () {
    Route::resource('/', TaskController::class)->parameters(['' => 'task']);
    Route::patch('/close/status/{task}', [TaskController::class, 'closeStatus']);
    Route::get('/close/status', [TaskController::class, 'indexCloseStatus']);
});

Route::prefix('v1/subtask/')->middleware('auth:api')->group(function () {
    Route::get('/{task}', [SubtaskController::class , 'index']);
    Route::post('/{task}', [SubtaskController::class , 'store']);
    Route::get('/show/{task}/{subtask}', [SubtaskController::class , 'show']);
    Route::put('/update/{task}/{subtask}', [SubtaskController::class , 'update']);
    Route::delete('/destroy/{task}/{subtask}', [SubtaskController::class , 'destroy']);
    Route::patch('/close/status/{task}/{subtask}', [SubtaskController::class, 'closeStatus']);
});

Route::prefix('/v1/category')->middleware('auth:api')->group(function () {
    Route::resource('/', CategoryController::class)->parameters(['' => 'category']);
});

Route::prefix('/v1/note')->middleware('auth:api')->group(function () {
    Route::get('/{task}', [NoteController::class , 'index']);
    Route::post('/{task}', [NoteController::class , 'store']);
    Route::get('/show/{task}/{note}', [NoteController::class , 'show']);
    Route::put('/update/{task}/{note}', [NoteController::class , 'update']);
    Route::delete('/destroy/{task}/{note}', [NoteController::class , 'destroy']);});

Route::prefix('/v1/group')->middleware('auth:api')->group(function () {
    Route::resource('/', GroupController::class)->parameters(['' => 'group']);
    Route::patch('/detach/user/{group}', [GroupController::class , 'detached_role']);
});
