<?php

use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('projects', [ProjectApiController::class, 'index']);
    Route::get('projects/{project}', [ProjectApiController::class, 'show']);
    Route::get('tasks', [TaskApiController::class, 'index']);
    Route::get('tasks/{task}', [TaskApiController::class, 'show']);
});

Route::get('health', fn () => response()->json(['status' => 'ok', 'time' => now()->toIso8601String()]));
