<x-subscriptions::layouts.master>
    <h2 class="mb-4">Choose a Plan</h2>
    <ul>
        @foreach($plans as $plan)
            <li class="mb-3">
                <div>
                    <strong>{{ $plan->name }}</strong>
                    <div>{{ $plan->description }}</div>
                </div>
                @foreach($plan->prices as $price)
                    <form action="{{ route('subscriptions.checkout') }}" method="POST" style="margin-top:8px">
                        @csrf
                        <span>
                            {{ strtoupper($price->currency) }} {{ number_format($price->amount/100, 2) }} / {{ $price->interval }}
                        </span>
                        <input type="hidden" name="price_id" value="{{ $price->stripe_price_id }}">
                        <button type="submit" {{ empty($price->stripe_price_id) ? 'disabled' : '' }}>Subscribe</button>
                    </form>
                @endforeach
            </li>
        @endforeach
    </ul>
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
</x-subscriptions::layouts.master>


