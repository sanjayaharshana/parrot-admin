<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscriptions\Http\Controllers\SubscriptionsController;



Route::resource('subscriptions', SubscriptionsController::class)->names('subscriptions');
