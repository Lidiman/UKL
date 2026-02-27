<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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

