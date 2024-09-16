<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Category\CategoryController;
use App\Http\Controllers\V1\Task\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user/')->group(function(){
    Route::post('/login' , [AuthController::class , 'login']);
    Route::post('/register' , [AuthController::class , 'register']);
})->middleware('auth:api')->group(function(){
    Route::post('/logout' , [AuthController::class , 'logout']);
});

Route::prefix('v1/user/task/')->middleware('auth:api')->group(function(){
    Route::resource('/' , TaskController::class)->parameters(['' => 'task']);
});

Route::prefix('v1/task/category')->middleware('auth:api')->group(function(){
    Route::resource('/' , CategoryController::class)->parameters(['' => 'category']);
});
