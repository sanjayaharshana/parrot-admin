<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::prefix('settings')->name('settings.')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/update', [SettingsController::class, 'update'])->name('update');
    Route::get('/reset', [SettingsController::class, 'reset'])->name('reset');
});
