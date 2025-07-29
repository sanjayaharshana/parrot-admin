<?php

use Illuminate\Support\Facades\Route;
use Modules\PluginManager\Http\Controllers\PluginManagerController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pluginmanagers', PluginManagerController::class)->names('pluginmanager');
});
