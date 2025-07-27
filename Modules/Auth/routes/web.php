<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot password route
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Legacy resource routes
Route::middleware(['auth', 'verified'])->group(function () {

});

Route::resource('auths', AuthController::class)->names('auth');
