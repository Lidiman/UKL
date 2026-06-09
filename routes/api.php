<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\FocusSessionController;

// Public API Routes
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    Route::get('/analytics/stats', [AnalyticsController::class, 'stats']);
    
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);         
        Route::post('/', [TaskController::class, 'store']);         
        Route::put('/{task}', [TaskController::class, 'update']);    
        Route::delete('/{task}', [TaskController::class, 'destroy']); 
        Route::get('/stats', [TaskController::class, 'stats']);      
    });

    // Project routes
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']);        
        Route::post('/', [ProjectController::class, 'store']);       
        Route::get('/{project}', [ProjectController::class, 'show']); 
        Route::put('/{project}', [ProjectController::class, 'update']); 
        Route::delete('/{project}', [ProjectController::class, 'destroy']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/generate-deadline-reminders', [NotificationController::class, 'generateDeadlineNotifications']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead']);
        Route::put('/{id}/mark-read', [NotificationController::class, 'markRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
    });

    // Focus Sessions
    Route::prefix('focus-sessions')->group(function () {
        Route::get('/', [FocusSessionController::class, 'index']);
        Route::post('/', [FocusSessionController::class, 'store']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
});

// Admin API routes (requires admin middleware)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
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