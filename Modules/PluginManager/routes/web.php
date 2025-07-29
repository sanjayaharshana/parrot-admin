<?php

use Illuminate\Support\Facades\Route;
use Modules\PluginManager\Http\Controllers\PluginManagerController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pluginmanagers', PluginManagerController::class)->names('pluginmanager');
});
