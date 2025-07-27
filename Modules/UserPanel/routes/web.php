<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('userpanels', UserPanelController::class)->names('userpanel');
});
