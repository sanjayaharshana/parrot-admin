@extends('landing::components.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Transform Your Business with Parrot Admin</h1>
            <p>The ultimate SaaS solution for modern businesses. Streamline operations, boost productivity, and scale with confidence.</p>
            <div class="cta-buttons">
                <a href="#pricing" class="btn btn-primary">Get Started Free</a>
                <a href="#features" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Why Choose Parrot Admin?</h2>
            <p class="section-subtitle">Powerful features designed to help your business grow and succeed in the digital age.</p>
            <div class="features-grid">
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Lightning Fast</h3>
                    <p>Built with modern technologies for optimal performance and speed. Your team will love the responsive interface.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Enterprise Security</h3>
                    <p>Bank-level security with encryption, two-factor authentication, and compliance with industry standards.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Advanced Analytics</h3>
                    <p>Get deep insights into your business performance with real-time analytics and customizable dashboards.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Team Collaboration</h3>
                    <p>Work together seamlessly with built-in collaboration tools, real-time updates, and team management features.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile First</h3>
                    <p>Access your dashboard anywhere, anytime with our mobile-optimized interface that works on all devices.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is always here to help you succeed with round-the-clock assistance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <h2 class="section-title">Simple, Transparent Pricing</h2>
            <p class="section-subtitle">Choose the plan that's right for your business. No hidden fees, no surprises.</p>
            
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
            
            <div class="pricing-grid">
                @forelse($plans as $plan)
                    @php
                        $monthlyPrice = $plan->prices->where('interval', 'month')->first();
                        $yearlyPrice = $plan->prices->where('interval', 'year')->first();
                        $isFeatured = $plan->slug === 'professional'; // Make Professional plan featured
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
            
            <!-- Feature Comparison Table -->
            @if($plans->count() > 0)
                <div class="feature-comparison">
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
            @endif
        </div>
    </section>

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
            <a href="#" class="btn btn-primary">Start Your Free Trial</a>
        </div>
    </section>
@endsection 