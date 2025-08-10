@extends('landing::components.layouts.app')

@section('content')
<div class="pricing-page">
    <!-- Hero Section -->
    <section class="pricing-hero">
        <div class="container">
            <h1 class="hero-title">Simple, Transparent Pricing</h1>
            <p class="hero-subtitle">Choose the plan that's right for your business. No hidden fees, no surprises.</p>

            <!-- Pricing Toggle -->
            <div class="pricing-toggle">
                <span class="toggle-label">Monthly</span>
                <label class="toggle-switch">
                    <input type="checkbox" id="pricing-toggle">
                    <span class="toggle-slider"></span>
                </label>
                <span class="toggle-label">Yearly <span class="toggle-savings">Save up to 20%</span></span>
            </div>

            <!-- Pricing Info -->
            <div class="pricing-info">
                <p class="pricing-note">
                    <i class="fas fa-info-circle"></i>
                    All plans include a 14-day free trial. No credit card required to start.
                </p>
            </div>
        </div>
    </section>

    <!-- Pricing Cards Section -->
    <section class="pricing-cards">
        <div class="container">
            <div class="pricing-grid">
                @forelse($plans as $plan)
                    @php
                        $monthlyPrice = $plan->prices->where('interval', 'month')->first();
                        $yearlyPrice = $plan->prices->where('interval', 'year')->first();
                        $isFeatured = $plan->slug === 'professional';
                    @endphp
                    <div class="pricing-card {{ $isFeatured ? 'featured' : '' }}" data-plan="{{ $plan->id }}"
                            data-monthly-price="{{ $monthlyPrice ? $monthlyPrice->stripe_price_id : '' }}"
                            data-yearly-price="{{ $yearlyPrice ? $yearlyPrice->stripe_price_id : '' }}">
                        @if($isFeatured)
                            <div class="popular-badge">Most Popular</div>
                        @endif
                        <div class="pricing-header">
                            <h3 class="plan-name">{{ $plan->name }}</h3>
                            <p class="plan-description">{{ $plan->description }}</p>
                            <div class="plan-price monthly-price" style="display: block;">
                                @if($monthlyPrice)
                                    <div class="price-amount">${{ number_format($monthlyPrice->amount / 100, 0) }}</div>
                                    <div class="plan-period">per month</div>
                                @else
                                    <div class="price-amount">-</div>
                                    <div class="plan-period">Contact Sales</div>
                                @endif
                            </div>
                            <div class="plan-price yearly-price" style="display: none;">
                                @if($yearlyPrice)
                                    <div class="price-amount">${{ number_format($yearlyPrice->amount / 100, 0) }}</div>
                                    <div class="plan-period">per year</div>
                                    <div class="plan-savings">
                                        <small>Save ${{ number_format(($monthlyPrice->amount * 12 - $yearlyPrice->amount) / 100, 0) }}/year</small>
                                    </div>
                                @else
                                    <div class="price-amount">-</div>
                                    <div class="plan-period">Contact Sales</div>
                                @endif
                            </div>
                        </div>
                        <ul class="pricing-features">
                            @forelse($plan->features as $feature)
                                <li>
                                    @if($feature->pivot->enabled)
                                        @if($feature->pivot->limit)
                                            {{ $feature->name }}: {{ $feature->pivot->limit }} {{ $feature->unit }}
                                        @else
                                            {{ $feature->name }}
                                        @endif
                                    @else
                                        <span class="feature-disabled">{{ $feature->name }}</span>
                                    @endif
                                </li>
                            @empty
                                <li>Core features included</li>
                            @endforelse
                        </ul>
                        <div class="pricing-actions">
                            @if($monthlyPrice || $yearlyPrice)
                                <form action="{{ route('subscriptions.checkout') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="price_id" value="{{ $monthlyPrice ? $monthlyPrice->stripe_price_id : $yearlyPrice->stripe_price_id }}">
                                    <button type="submit" class="btn {{ $isFeatured ? 'btn-primary' : 'btn-secondary' }}">
                                        Get Started
                                    </button>
                                </form>
                            @else
                                <span class="btn btn-disabled">Contact Sales</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="plan-name">Coming Soon</h3>
                            <div class="plan-price">-</div>
                            <div class="plan-period">pricing in development</div>
                        </div>
                        <ul class="pricing-features">
                            <li>Pricing plans coming soon</li>
                            <li>Stay tuned for updates</li>
                        </ul>
                        <a href="#contact" class="btn btn-secondary">Contact Us</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Feature Comparison Table -->
    @if($plans->count() > 0)
        <section class="feature-comparison">
            <div class="container">
                <h3 class="comparison-title">Feature Comparison</h3>
                <div class="comparison-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Features</th>
                                @foreach($plans as $plan)
                                    <th>{{ $plan->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allFeatures = $plans->flatMap->features->unique('key');
                            @endphp
                            @foreach($allFeatures as $feature)
                                <tr>
                                    <td class="feature-name">{{ $feature->name }}</td>
                                    @foreach($plans as $plan)
                                        @php
                                            $planFeature = $plan->features->where('key', $feature->key)->first();
                                            $isEnabled = $planFeature && $planFeature->pivot->enabled;
                                            $limit = $planFeature ? $planFeature->pivot->limit : null;
                                        @endphp
                                        <td class="feature-value">
                                            @if($isEnabled)
                                                @if($limit)
                                                    <span class="feature-enabled">{{ $limit }} {{ $feature->unit }}</span>
                                                @else
                                                    <span class="feature-enabled">✓</span>
                                                @endif
                                            @else
                                                <span class="feature-disabled">✗</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h3 class="faq-question">Can I change my plan at any time?</h3>
                    <p class="faq-answer">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately and are prorated.</p>
                </div>
                <div class="faq-item">
                    <h3 class="faq-question">Is there a free trial?</h3>
                    <p class="faq-answer">Yes! All plans include a 14-day free trial. No credit card required to start your trial.</p>
                </div>
                <div class="faq-item">
                    <h3 class="faq-question">What payment methods do you accept?</h3>
                    <p class="faq-answer">We accept all major credit cards (Visa, MasterCard, American Express) and PayPal.</p>
                </div>
                <div class="faq-item">
                    <h3 class="faq-question">Can I cancel anytime?</h3>
                    <p class="faq-answer">Absolutely! You can cancel your subscription at any time with no cancellation fees.</p>
                </div>
                <div class="faq-item">
                    <h3 class="faq-question">Do you offer refunds?</h3>
                    <p class="faq-answer">We offer a 30-day money-back guarantee if you're not satisfied with our service.</p>
                </div>
                <div class="faq-item">
                    <h3 class="faq-question">Is my data secure?</h3>
                    <p class="faq-answer">Yes, we use bank-level encryption and security measures to protect your data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of businesses already using Parrot Admin to grow their operations.</p>
            <a href="#pricing" class="btn btn-primary">Start Your Free Trial</a>
        </div>
    </section>
</div>

<!-- Pricing Page Styles -->
<style>
.pricing-page {
    background: #f7fafc;
}

.pricing-hero {
    padding: 5rem 0 3rem;
    text-align: center;
    background: white;
    border-bottom: 1px solid #e2e8f0;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: #4a5568;
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.pricing-cards {
    padding: 4rem 0;
}

.pricing-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 50px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.toggle-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.875rem;
}

.toggle-savings {
    background: #48bb78;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
    margin-left: 0.5rem;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e2e8f0;
    transition: 0.4s;
    border-radius: 34px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #667eea;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.pricing-info {
    text-align: center;
    margin-bottom: 2rem;
}

.pricing-note {
    color: #718096;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: rgba(102, 126, 234, 0.1);
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    max-width: 500px;
    margin: 0 auto;
}

.pricing-note i {
    color: #667eea;
}

.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.pricing-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    border: 2px solid #e2e8f0;
    max-width: 350px;
    margin: 0 auto;
    height: fit-content;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: #cbd5e0;
}

.pricing-card.featured {
    border-color: #667eea;
    transform: scale(1.05);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.pricing-card.featured:hover {
    transform: scale(1.05) translateY(-5px);
    box-shadow: 0 25px 50px rgba(102, 126, 234, 0.4);
}

.popular-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 10;
}

.pricing-header {
    margin-bottom: 1.5rem;
}

.plan-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
}

.plan-description {
    color: #718096;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    min-height: 2.5rem;
}

.plan-price {
    margin-bottom: 1.5rem;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 700;
    color: #667eea;
    line-height: 1;
}

.plan-period {
    color: #718096;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.plan-savings {
    margin-top: 0.5rem;
    padding: 0.25rem 0.75rem;
    background: #48bb78;
    color: white;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
}

.pricing-features {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    text-align: left;
    flex-grow: 1;
}

.pricing-features li {
    padding: 0.5rem 0;
    color: #4a5568;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
}

.pricing-features li::before {
    content: '✓';
    color: #48bb78;
    font-weight: bold;
    margin-right: 0.75rem;
    font-size: 1rem;
}

.pricing-features li:last-child {
    border-bottom: none;
}

.feature-disabled {
    color: #a0aec0;
    text-decoration: line-through;
}

.pricing-actions {
    margin-top: 1.5rem;
}

.pricing-actions .btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    min-width: 120px;
}

.pricing-actions form.inline {
    display: inline;
}

.pricing-actions form.inline button {
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    font: inherit;
    cursor: pointer;
}

.pricing-actions .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.pricing-actions .btn-secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.pricing-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.pricing-actions .btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
}

.pricing-actions .btn:disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}

.btn-disabled {
    background: #e2e8f0 !important;
    color: #a0aec0 !important;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}

/* Feature Comparison Table */
.feature-comparison {
    margin-top: 4rem;
    padding: 3rem 0;
    background: white;
}

.comparison-title {
    text-align: center;
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 2rem;
    color: #1a202c;
}

.comparison-table {
    overflow-x: auto;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.comparison-table table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.comparison-table th,
.comparison-table td {
    padding: 1rem;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}

.comparison-table th {
    background: #f7fafc;
    font-weight: 600;
    color: #1a202c;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.comparison-table th:first-child {
    text-align: left;
    background: #667eea;
    color: white;
}

.comparison-table td:first-child {
    text-align: left;
    font-weight: 500;
    color: #4a5568;
}

.feature-enabled {
    color: #48bb78;
    font-weight: 600;
}

.feature-disabled {
    color: #a0aec0;
}

/* FAQ Section */
.faq-section {
    padding: 5rem 0;
    background: white;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.faq-item {
    background: #f7fafc;
    padding: 2rem;
    border-radius: 15px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.faq-question {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 1rem;
}

.faq-answer {
    color: #4a5568;
    line-height: 1.6;
}

/* CTA Section */
.cta-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
}

.cta-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-section p {
    font-size: 1.125rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-section .btn {
    background: white;
    color: #667eea;
    padding: 1rem 2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.125rem;
    transition: all 0.3s ease;
}

.cta-section .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }

    .pricing-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 1rem;
    }

    .pricing-card {
        max-width: 100%;
        padding: 1.5rem;
    }

    .pricing-card.featured {
        transform: none;
    }

    .pricing-card.featured:hover {
        transform: translateY(-2px);
    }

    .price-amount {
        font-size: 2rem;
    }

    .plan-name {
        font-size: 1.25rem;
    }

    .pricing-toggle {
        flex-direction: column;
        gap: 1rem;
        padding: 1.5rem;
    }

    .comparison-table {
        margin: 0 -1rem;
        border-radius: 0;
    }

    .comparison-table table {
        min-width: 500px;
    }

    .comparison-table th,
    .comparison-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }

    .faq-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .faq-item {
        padding: 1.5rem;
    }
}
</style>

<!-- Pricing Page JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Pricing Page Loaded');

    // Pricing toggle functionality
    const pricingToggle = document.getElementById('pricing-toggle');
    if (pricingToggle) {
        pricingToggle.addEventListener('change', function() {
            const isYearly = this.checked;
            const monthlyPrices = document.querySelectorAll('.monthly-price');
            const yearlyPrices = document.querySelectorAll('.yearly-price');

            monthlyPrices.forEach(price => {
                price.style.display = isYearly ? 'none' : 'block';
            });

            yearlyPrices.forEach(price => {
                price.style.display = isYearly ? 'block' : 'none';
            });

            // Update hidden price_id fields in forms
            document.querySelectorAll('.pricing-card').forEach(card => {
                const monthlyPriceId = card.dataset.monthlyPrice;
                const yearlyPriceId = card.dataset.yearlyPrice;
                const form = card.querySelector('form');
                const priceInput = form ? form.querySelector('input[name="price_id"]') : null;

                if (priceInput && monthlyPriceId && yearlyPriceId) {
                    priceInput.value = isYearly ? yearlyPriceId : monthlyPriceId;
                }
            });

            // Add smooth transition effect
            document.querySelectorAll('.pricing-card').forEach(card => {
                card.style.transition = 'all 0.3s ease';
            });
        });
    }

    // Add loading state to checkout forms
    document.querySelectorAll('.pricing-actions form').forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button');
            if (button) {
                const originalText = button.textContent;
                button.textContent = 'Processing...';
                button.disabled = true;
                button.style.opacity = '0.7';

                // Re-enable button after 5 seconds (fallback)
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.opacity = '1';
                }, 5000);
            }
        });
    });
});
</script>
@endsection
