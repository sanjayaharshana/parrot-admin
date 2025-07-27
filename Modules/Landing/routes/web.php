<?php

use Illuminate\Support\Facades\Route;
use Modules\Landing\Http\Controllers\LandingController;


Route::resource('/', LandingController::class)->names('landing');
