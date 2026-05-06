<?php
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ProfileController; 

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/courses', [CourseController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);

    Route::post('/courses', [CourseController::class, 'store']);

    Route::post('/profile', [ProfileController::class, 'store']);

    Route::get('/profile', [ProfileController::class, 'show']);

    Route::put('/profile', [ProfileController::class, 'update']);
});
