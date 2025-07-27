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
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Starter</h3>
                        <div class="plan-price">$29</div>
                        <div class="plan-period">per month</div>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 5 team members</li>
                        <li>Basic analytics</li>
                        <li>Email support</li>
                        <li>10GB storage</li>
                        <li>Core features</li>
                    </ul>
                    <a href="#" class="btn btn-secondary">Start Free Trial</a>
                </div>
                <div class="pricing-card featured">
                    <div class="pricing-header">
                        <h3 class="plan-name">Professional</h3>
                        <div class="plan-price">$79</div>
                        <div class="plan-period">per month</div>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 25 team members</li>
                        <li>Advanced analytics</li>
                        <li>Priority support</li>
                        <li>100GB storage</li>
                        <li>All features</li>
                        <li>Custom integrations</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Start Free Trial</a>
                </div>
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Enterprise</h3>
                        <div class="plan-price">$199</div>
                        <div class="plan-period">per month</div>
                    </div>
                    <ul class="pricing-features">
                        <li>Unlimited team members</li>
                        <li>Custom analytics</li>
                        <li>Dedicated support</li>
                        <li>Unlimited storage</li>
                        <li>All features</li>
                        <li>Custom development</li>
                        <li>SLA guarantee</li>
                    </ul>
                    <a href="#" class="btn btn-secondary">Contact Sales</a>
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