<?php

namespace Modules\Subscriptions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Cashier\Checkout;
use Laravel\Cashier\Cashier;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subscriptions::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subscriptions::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('subscriptions::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('subscriptions::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function plans()
    {
        // Expect these envs to be set with Stripe Price IDs
        $plans = [
            [
                'name' => 'Basic Monthly',
                'price_id' => env('STRIPE_PRICE_BASIC_MONTHLY'),
                'interval' => 'month',
            ],
            [
                'name' => 'Basic Yearly',
                'price_id' => env('STRIPE_PRICE_BASIC_YEARLY'),
                'interval' => 'year',
            ],
        ];

        return view('subscriptions::plans', compact('plans'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'price_id' => ['required', 'string'],
        ]);

        $user = Auth::user();
        abort_unless($user, 403);

        $priceId = (string) $request->input('price_id');

        // Create Checkout session for subscription
        $checkout = $user->checkout([
            $priceId => 1,
        ], [
            'success_url' => route('subscriptions.success'),
            'cancel_url' => route('subscriptions.cancel'),
            'mode' => 'subscription',
        ]);

        return redirect()->away($checkout->url);
    }

    public function success()
    {
        return view('subscriptions::success');
    }

    public function cancel()
    {
        return view('subscriptions::cancel');
    }

    public function swap(Request $request)
    {
        $request->validate([
            'price_id' => ['required', 'string'],
        ]);

        $user = Auth::user();
        abort_unless($user, 403);

        $subscriptionName = 'default';
        if (! $user->subscribed($subscriptionName)) {
            return back()->with('error', 'No active subscription.');
        }

        $priceId = (string) $request->input('price_id');
        $user->subscription($subscriptionName)->swap($priceId);

        return back()->with('status', 'Subscription updated.');
    }

    public function cancelNow()
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $subscriptionName = 'default';
        if ($user->subscribed($subscriptionName)) {
            $user->subscription($subscriptionName)->cancel();
        }

        return back()->with('status', 'Subscription cancelled.');
    }
}
