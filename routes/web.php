<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/task-manager', function () {
    return view('task-manager');
})->middleware('auth');

Route::get('/focus', function () {
    return view('focus');
})->middleware('auth');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth');

Route::get('/analytics', function () {
    return view('analytics');
})->middleware('auth');

Route::get('/projects', function () {
    return view('projects');
})->middleware('auth');

// Admin Routes
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin']);

Route::get('/admin/users', function () {
    return view('admin.users');
})->middleware(['auth', 'admin']);

Route::get('/admin/tasks', function () {
    return view('admin.tasks');
})->middleware(['auth', 'admin']);

// Admin Web-API Routes (session auth, no Bearer token needed)
Route::middleware(['auth', 'admin'])->prefix('web-api')->group(function () {
    // Stats
    Route::get('/admin/stats', [AdminController::class, 'stats']);

    // Users CRUD
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [AdminController::class, 'usersIndex']);
        Route::post('/', [AdminController::class, 'usersStore']);
        Route::get('/{user}/detail', [AdminController::class, 'userDetail']);
        Route::get('/{user}', [AdminController::class, 'usersShow']);
        Route::put('/{user}', [AdminController::class, 'usersUpdate']);
        Route::delete('/{user}', [AdminController::class, 'usersDestroy']);
    });

    // Tasks CRUD
    Route::prefix('admin/tasks')->group(function () {
        Route::get('/', [AdminController::class, 'tasksIndex']);
        Route::post('/', [AdminController::class, 'tasksStore']);
        Route::get('/{task}', [AdminController::class, 'tasksShow']);
        Route::put('/{task}', [AdminController::class, 'tasksUpdate']);
        Route::delete('/{task}', [AdminController::class, 'tasksDestroy']);
    });
});
