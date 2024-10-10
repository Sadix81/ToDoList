<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Category\CategoryController;
use App\Http\Controllers\V1\Group\GroupController;
use App\Http\Controllers\V1\Note\NoteController;
use App\Http\Controllers\V1\Profile\ProfileController;
use App\Http\Controllers\V1\Task\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user/')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
})->middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('profile/show', [ProfileController::class, 'show']);
    Route::post('profile/update/{user}', [ProfileController::class, 'update']);
});

Route::prefix('v1/task/')->middleware('auth:api')->group(function () {
    Route::resource('/', TaskController::class)->parameters(['' => 'task']);
    Route::post('/close/statsu/{task}', [TaskController::class, 'closeStatus']);
    Route::get('/close/statsu', [TaskController::class, 'indexCloseStatus']);
});

Route::prefix('/v1/category')->middleware('auth:api')->group(function () {
    Route::resource('/', CategoryController::class)->parameters(['' => 'category']);
});

Route::prefix('/v1/note')->middleware('auth:api')->group(function () {
    Route::resource('/', NoteController::class)->parameters(['' => 'note']);
});

Route::prefix('/v1/group')->middleware('auth:api')->group(function () {
    Route::resource('/', GroupController::class)->parameters(['' => 'group']);
});
