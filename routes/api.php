<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(UserController::class)->group(function() {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
    Route::post('/auth/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/auth/edit', 'edit')->middleware('auth:sanctum');
    Route::post('/auth/reset-password', 'resetPassword')->middleware('auth:sanctum');
});

Route::controller(TaskController::class)->group(function () {
    Route::get('/task/get-all', 'getAll')->middleware('auth:sanctum');
    Route::get('/task/get-id', 'getId');
    Route::post('/task/add', 'add')->middleware('auth:sanctum');
    Route::get('/task/search', 'search')->middleware('auth:sanctum');
    Route::post('/task/{id}/edit', 'update')->middleware('auth:sanctum');
    Route::get('/task/{id}/delete', 'delete');
    Route::get('/task/{id}/get-steps', 'getSteps');
    Route::get('/task/{id}/add-step', 'addStep');
    Route::post('/task/{id}/add-steps', 'addSteps');
    Route::post('/task/{id}/edit-steps', 'updateSteps');
    Route::get('/task/{id}/delete-step/{stepId}', 'deleteStep');
    Route::get('/task/{id}/delete-steps', 'deleteSteps');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/get-all', 'getAll')->middleware('auth:sanctum');
    Route::get('/category/add', 'add')->middleware('auth:sanctum');
    Route::get('/category/{id}/edit', 'update')->middleware('auth:sanctum');
    Route::get('/category/get-id', 'getId');
    Route::get('/category/{id}/delete', 'delete');
});

Route::controller(PriorityController::class)->group(function () {
    Route::get('/priority/get-all', 'getAll')->middleware('auth:sanctum');
    Route::get('/priority/add', 'add')->middleware('auth:sanctum');
    Route::get('/priority/{id}/edit', 'update')->middleware('auth:sanctum');
    Route::get('/priority/get-id', 'getId');
    Route::get('/priority/{id}/delete', 'delete');
});

Route::controller(StatusController::class)->group(function () {
    Route::get('/status/get-all', 'getAll');
    // Route::get('/status/add', 'add');
});

// Route::controller(StepController::class)->group(callback: function () {
//     Route::get('/step/{id}/get-all', 'getTaskSteps');
//     Route::get('/step/{id}/delete', 'deleteTaskSteps');
//     Route::get('/step/{id}/add-all', 'addTaskSteps');
//     Route::get('/step/{id}/add', 'add');
// });

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/stats', 'getStats')->middleware('auth:sanctum');
    Route::get('/dashboard/recent-tasks', 'getRecentTasks')->middleware('auth:sanctum');
    Route::get('/dashboard/completed-tasks', 'getCompletedTasks')->middleware('auth:sanctum');
});



Route::get('health', function (){
    return response("API is working");
} );