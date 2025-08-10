<?php

namespace Modules\Subscriptions\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Modules\Subscriptions\Models\UserInvoice;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle the invoice.payment_succeeded Stripe event.
     */
    public function handleInvoicePaymentSucceeded(array $payload)
    {
        $invoice = $payload['data']['object'] ?? null;
        if (! $invoice) {
            return $this->successMethod();
        }

        $customerId = $invoice['customer'] ?? null;
        if ($customerId) {
            $user = User::where('stripe_id', $customerId)->first();
            if ($user) {
                UserInvoice::updateOrCreate(
                    ['user_id' => $user->id, 'invoice_id' => $invoice['id']],
                    [
                        'status' => $invoice['status'] ?? null,
                        'amount_due' => $invoice['amount_due'] ?? null,
                        'amount_paid' => $invoice['amount_paid'] ?? null,
                        'currency' => $invoice['currency'] ?? null,
                        'due_date' => isset($invoice['due_date']) ? \Carbon\Carbon::createFromTimestamp($invoice['due_date']) : null,
                        'paid_at' => (isset($invoice['status']) && $invoice['status'] === 'paid') && isset($invoice['status_transitions']['paid_at'])
                            ? \Carbon\Carbon::createFromTimestamp($invoice['status_transitions']['paid_at'])
                            : null,
                        'data' => $invoice,
                    ]
                );
            }
        }

        return parent::handleInvoicePaymentSucceeded($payload);
    }
}


