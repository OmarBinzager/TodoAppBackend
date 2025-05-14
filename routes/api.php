<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(TaskController::class)->group(function () {
    Route::get('/task/get-all', 'getAll');
    Route::get('/task/get-id', 'getId');
    Route::get('/task/add', 'add');
    Route::get('/task/delete/{id}', 'delete');
    Route::get('/task/get-steps', 'getSteps');
    Route::get('/task/add-step', 'addStep');
    Route::get('/task/add-steps', 'addSteps');
    Route::get('/task/update-step', 'updateStep');
    Route::get('/task/delete-step', 'deleteStep');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/get-all', 'getAll');
    Route::get('/category/add', 'add');
    Route::get('/category/edit', 'update');
    Route::get('/category/get-id', 'getId');
    Route::get('/category/delete/{id}', 'delete');
});

Route::controller(PriorityController::class)->group(function () {
    Route::get('/priority/get-all', 'getAll');
    Route::get('/priority/add', 'add');
    Route::get('/priority/edit', 'update');
    Route::get('/priority/get-id', 'getId');
    Route::get('/priority/delete/{id}', 'delete');
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
    Route::get('/dashboard/stats', 'getStats');
    Route::get('/dashboard/recent-tasks', 'getRecentTasks');
    Route::get('/dashboard/completed-tasks', 'getCompletedTasks');
});