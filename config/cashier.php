<?php

return [
    // Public key for Stripe.js
    'key' => env('STRIPE_KEY'),

    // Secret key used by server-side API calls
    'secret' => env('STRIPE_SECRET'),

    // Optional webhook secret (Cashier also reads services.stripe.webhook.secret)
    'webhook' => [
        'secret' => env('STRIPE_WEBHOOK_SECRET'),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],
];


