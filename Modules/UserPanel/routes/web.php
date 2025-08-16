<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;
use Modules\UserPanel\Http\Controllers\CallbackLayoutController;
use Modules\UserPanel\Http\Controllers\CallableOptionsController;
use Modules\UserPanel\Http\Controllers\CustomContentController;
use Modules\UserPanel\Http\Controllers\ModelBindingController;
use Modules\UserPanel\Http\Controllers\DataViewController;
use Modules\UserPanel\Http\Controllers\SimpleLayoutController;
use Modules\UserPanel\Http\Controllers\TestController;
use Modules\UserPanel\Http\Controllers\MediaController;
use Modules\UserPanel\Http\Controllers\CKEditorDemoController;
use Modules\UserPanel\Http\Controllers\CKEditorTestController;
use Modules\UserPanel\Http\Controllers\CKEditorDebugController;
use Modules\UserPanel\Http\Controllers\HeightTestController;
use Modules\UserPanel\Http\Controllers\SelectTestController;
use Modules\UserPanel\Http\Controllers\SwitchTestController;

Route::middleware(['auth', 'verified'])->group(function () {

    // Media manager endpoints
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/dashboard', [\Modules\UserPanel\Http\Controllers\DashboardController::class,'index'])->name('dashboard.index');

});
