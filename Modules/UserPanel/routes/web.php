<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [UserPanelController::class, 'dashboard'])->name('userpanel.dashboard');
    Route::get('/settings', [UserPanelController::class, 'settings'])->name('userpanel.settings');
    Route::post('/settings', [UserPanelController::class, 'updateSettings'])->name('userpanel.settings.update');
    Route::get('/subscription', [UserPanelController::class, 'subscription'])->name('userpanel.subscription');
    
    // Legacy resource routes
    Route::resource('userpanels', UserPanelController::class)->names('userpanel');
});
