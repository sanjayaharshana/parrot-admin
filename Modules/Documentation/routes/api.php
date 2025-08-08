<?php

use Illuminate\Support\Facades\Route;
use Modules\Documentation\Http\Controllers\DocumentationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('documentations', DocumentationController::class)->names('documentation');
});
