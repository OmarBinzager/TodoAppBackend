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
    Route::get('/tasks/get-all', 'getAll');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/get-all', 'getAll');
});

Route::controller(PriorityController::class)->group(function () {
    Route::get('/priority/get-all', 'getAll');
});

Route::controller(StatusController::class)->group(function () {
    Route::get('/status/get-all', 'getAll');
});

Route::controller(StepController::class)->group(function () {
    Route::get('/step/get-all', 'getAll');
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/stats', 'getStats');
    Route::get('/dashboard/recent-tasks', 'getRecentTasks');
    Route::get('/dashboard/completed-tasks', 'getCompletedTasks');
});