@extends('landing::components.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>About Parrot Admin</h1>
            <p>Learn more about our mission to transform business operations through innovative SaaS solutions.</p>
            <div class="cta-buttons">
                <a href="#story" class="btn btn-primary">Our Story</a>
                <a href="#team" class="btn btn-secondary">Meet the Team</a>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="features" id="story">
        <div class="container">
            <h2 class="section-title">Our Story</h2>
            <p class="section-subtitle">From a simple idea to a powerful platform that's helping thousands of businesses grow.</p>
            <div class="features-grid">
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>The Beginning</h3>
                    <p>Founded in 2020, Parrot Admin started as a solution to the complex challenges modern businesses face in managing their operations.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Growing Community</h3>
                    <p>Today, we serve thousands of businesses worldwide, from startups to enterprise organizations, helping them streamline their operations.</p>
                </div>
                <div class="feature-card animate-fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Future Vision</h3>
                    <p>We're constantly innovating and expanding our platform to meet the evolving needs of modern businesses.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="pricing" id="team">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <p class="section-subtitle">The passionate individuals behind Parrot Admin's success.</p>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">John Doe</h3>
                        <div class="plan-price">CEO & Founder</div>
                        <div class="plan-period">10+ years experience</div>
                    </div>
                    <p>Visionary leader with a passion for creating innovative solutions that drive business growth.</p>
                </div>
                <div class="pricing-card featured">
                    <div class="pricing-header">
                        <h3 class="plan-name">Jane Smith</h3>
                        <div class="plan-price">CTO</div>
                        <div class="plan-period">15+ years experience</div>
                    </div>
                    <p>Technical expert responsible for building scalable and secure solutions that our customers trust.</p>
                </div>
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Mike Johnson</h3>
                        <div class="plan-price">Head of Product</div>
                        <div class="plan-period">8+ years experience</div>
                    </div>
                    <p>Product strategist focused on creating intuitive user experiences that delight our customers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Our Values</h2>
            <p>Innovation, integrity, and customer success drive everything we do.</p>
            <div class="features-grid" style="margin-top: 3rem;">
                <div class="feature-card" style="background: rgba(255,255,255,0.1); color: white;">
                    <div class="feature-icon" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Customer First</h3>
                    <p>Every decision we make is guided by what's best for our customers and their success.</p>
                </div>
                <div class="feature-card" style="background: rgba(255,255,255,0.1); color: white;">
                    <div class="feature-icon" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security & Trust</h3>
                    <p>We prioritize the security and privacy of our customers' data above all else.</p>
                </div>
                <div class="feature-card" style="background: rgba(255,255,255,0.1); color: white;">
                    <div class="feature-icon" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>We constantly push boundaries to deliver cutting-edge solutions that drive business growth.</p>
                </div>
            </div>
        </div>
    </section>
@endsection 