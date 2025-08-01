<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parrot Admin - Modern SaaS Solution</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #333;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            color: inherit;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: inherit;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: #667eea;
        }

        .nav-menu .btn-get-started {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .nav-menu .btn-get-started:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }

        .header.scrolled .nav-menu .btn-get-started {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .header.scrolled .nav-menu .btn-get-started:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: inherit;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8rem 0 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }

        /* Features Section */
        .features {
            padding: 5rem 0;
            background: #f8fafc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1a202c;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.25rem;
            color: #718096;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a202c;
        }

        .feature-card p {
            color: #718096;
            line-height: 1.6;
        }

        /* Pricing Section */
        .pricing {
            padding: 5rem 0;
            background: white;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .pricing-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .pricing-card.featured {
            border-color: #667eea;
            transform: scale(1.05);
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-5px);
        }

        .pricing-header {
            margin-bottom: 2rem;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1a202c;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .plan-period {
            color: #718096;
        }

        .pricing-features {
            list-style: none;
            margin: 2rem 0;
        }

        .pricing-features li {
            padding: 0.5rem 0;
            color: #4a5568;
        }

        .pricing-features li::before {
            content: '✓';
            color: #48bb78;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: #1a202c;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-section p,
        .footer-section a {
            color: #a0aec0;
            text-decoration: none;
            line-height: 1.6;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #2d3748;
            padding-top: 1rem;
            text-align: center;
            color: #a0aec0;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: #2d3748;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #667eea;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                flex-direction: column;
                padding: 2rem;
                gap: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .nav-menu.show {
                display: flex;
            }

            .nav-menu a {
                color: #333;
                padding: 0.5rem 0;
                width: 100%;
                text-align: center;
            }

            .nav-menu .btn-get-started {
                background: #667eea;
                color: white;
                border-color: #667eea;
                margin-top: 1rem;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .section-title {
                font-size: 2rem;
            }

            .pricing-card.featured {
                transform: none;
            }

            .pricing-card.featured:hover {
                transform: translateY(-5px);
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <nav class="nav-container">
            <a href="#" class="logo">Parrot Admin</a>
            <ul class="nav-menu">
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="/login" class="btn-get-started">Get Started</a></li>
            </ul>
            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>

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

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Parrot Admin</h3>
                    <p>Transform your business with our modern SaaS solution. Built for growth, designed for success.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Product</h3>
                    <p><a href="#features">Features</a></p>
                    <p><a href="#pricing">Pricing</a></p>
                    <p><a href="#">API</a></p>
                    <p><a href="#">Documentation</a></p>
                </div>
                <div class="footer-section">
                    <h3>Company</h3>
                    <p><a href="#about">About</a></p>
                    <p><a href="#">Blog</a></p>
                    <p><a href="#">Careers</a></p>
                    <p><a href="#">Press</a></p>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <p><a href="#">Help Center</a></p>
                    <p><a href="#">Contact Us</a></p>
                    <p><a href="#">Status</a></p>
                    <p><a href="#">Security</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Parrot Admin. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const navMenu = document.querySelector('.nav-menu');
        
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('show');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('show');
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe all feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional initialization code here
            console.log('Parrot Admin Landing Page Loaded');
        });

        // Form handling (if needed)
        function handleContactForm(event) {
            event.preventDefault();
            // Add your form handling logic here
            alert('Thank you for your message! We\'ll get back to you soon.');
        }

        // Pricing toggle (if needed)
        function togglePricing() {
            const pricingCards = document.querySelectorAll('.pricing-card');
            pricingCards.forEach(card => {
                // Add pricing toggle logic here
            });
        }
    </script>
</body>
</html>
