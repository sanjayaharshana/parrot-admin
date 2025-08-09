<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscriptions\Http\Controllers\SubscriptionsController;

// Cashier webhook
Route::post('stripe/webhook', [\Laravel\Cashier\Http\Controllers\WebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook');



Route::middleware(['web', 'auth'])->group(function () {
    Route::get('subscriptions/plans', [SubscriptionsController::class, 'plans'])->name('subscriptions.plans');
    Route::post('subscriptions/checkout', [SubscriptionsController::class, 'checkout'])->name('subscriptions.checkout');
    Route::get('subscriptions/success', [SubscriptionsController::class, 'success'])->name('subscriptions.success');
    Route::get('subscriptions/cancel', [SubscriptionsController::class, 'cancel'])->name('subscriptions.cancel');

    Route::post('subscriptions/swap', [SubscriptionsController::class, 'swap'])->name('subscriptions.swap');
    Route::post('subscriptions/cancel_now', [SubscriptionsController::class, 'cancelNow'])->name('subscriptions.cancel_now');
});

Route::resource('subscriptions', SubscriptionsController::class)->names('subscriptions');
