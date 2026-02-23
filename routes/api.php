<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function () {
    // Task API endpoints
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);          // Get all tasks
        Route::post('/', [TaskController::class, 'store']);         // Create task
        Route::put('{task}', [TaskController::class, 'update']);    // Update task
        Route::delete('{task}', [TaskController::class, 'destroy']); // Delete task
        Route::get('/stats', [TaskController::class, 'stats']);     // Get task stats
    });

    // User info endpoint
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
});
