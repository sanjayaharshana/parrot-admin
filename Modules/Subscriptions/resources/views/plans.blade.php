<x-subscriptions::layouts.master>
    <h2 class="mb-4">Choose a Plan</h2>
    <ul>
        @foreach($plans as $plan)
            <li class="mb-3">
                <form action="{{ route('subscriptions.checkout') }}" method="POST">
                    @csrf
                    <span>{{ $plan['name'] }}</span>
                    <input type="hidden" name="price_id" value="{{ $plan['price_id'] }}">
                    <button type="submit">Subscribe</button>
                </form>
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


