<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;


Route::middleware(['web', 'auth'])->group(function () {
    // Task API endpoints
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);          // Get all tasks
        Route::post('/', [TaskController::class, 'store']);         // Create task
        Route::put('/{task}', [TaskController::class, 'update']);    // Update task
        Route::delete('/{task}', [TaskController::class, 'destroy']); // Delete task
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

// Admin API routes (requires admin middleware)
Route::middleware(['web', 'auth:default', 'admin'])->group(function () {
    // Admin Users CRUD
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [AdminController::class, 'usersIndex']);
        Route::post('/', [AdminController::class, 'usersStore']);
        Route::get('/{user}', [AdminController::class, 'usersShow']);
        Route::put('/{user}', [AdminController::class, 'usersUpdate']);
        Route::delete('/{user}', [AdminController::class, 'usersDestroy']);
    });

    // Admin Tasks CRUD
    Route::prefix('admin/tasks')->group(function () {
        Route::get('/', [AdminController::class, 'tasksIndex']);
        Route::post('/', [AdminController::class, 'tasksStore']);
        Route::get('/{task}', [AdminController::class, 'tasksShow']);
        Route::put('/{task}', [AdminController::class, 'tasksUpdate']);
        Route::delete('/{task}', [AdminController::class, 'tasksDestroy']);
    });

    // Admin Stats
    Route::get('/admin/stats', [AdminController::class, 'stats']);
});


//Fixed the middleware for API routes to ensure proper authentication and admin access control.