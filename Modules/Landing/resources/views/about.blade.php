<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Parrot Admin</title>
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

        .nav-menu a.active {
            color: #667eea;
            font-weight: 600;
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

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Section Styles */
        .section {
            padding: 5rem 0;
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

        /* Story Section */
        .story {
            background: #f8fafc;
        }

        .story-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .story-text h3 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #1a202c;
        }

        .story-text p {
            font-size: 1.1rem;
            color: #4a5568;
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .story-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }

        .story-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        /* Mission Section */
        .mission {
            background: white;
        }

        .mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .mission-card {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .mission-icon {
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

        .mission-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a202c;
        }

        .mission-card p {
            color: #718096;
            line-height: 1.6;
        }

        /* Team Section */
        .team {
            background: #f8fafc;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .team-member {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .team-photo {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .team-info {
            padding: 1.5rem;
            text-align: center;
        }

        .team-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1a202c;
        }

        .team-info .position {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .team-info p {
            color: #718096;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 35px;
            height: 35px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            background: white;
            padding: 4rem 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1a202c;
        }

        .cta-section p {
            font-size: 1.25rem;
            color: #718096;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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

        .footer-social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .footer-social-links a {
            width: 40px;
            height: 40px;
            background: #2d3748;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .footer-social-links a:hover {
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

            .story-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .story-image {
                height: 300px;
            }

            .section-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-item h3 {
                font-size: 2.5rem;
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
            <a href="/" class="logo">Parrot Admin</a>
            <ul class="nav-menu">
                <li><a href="/#features">Features</a></li>
                <li><a href="/#pricing">Pricing</a></li>
                <li><a href="/about" class="active">About</a></li>
                <li><a href="/#contact">Contact</a></li>
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
            <h1>About Parrot Admin</h1>
            <p>We're on a mission to transform how businesses manage their operations through innovative SaaS solutions.</p>
        </div>
    </section>

    <!-- Story Section -->
    <section class="story section">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <h3>Our Story</h3>
                    <p>Founded in 2020, Parrot Admin was born from a simple observation: businesses were struggling with complex, outdated management systems that slowed them down instead of accelerating their growth.</p>
                    <p>Our team of passionate developers, designers, and business experts came together with a shared vision: to create a SaaS platform that would be powerful enough for enterprise needs, yet simple enough for small teams to adopt immediately.</p>
                    <p>Today, we serve thousands of businesses worldwide, helping them streamline operations, boost productivity, and scale with confidence. Our commitment to innovation and customer success drives everything we do.</p>
                </div>
                <div class="story-image">
                    <i class="fas fa-rocket"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission section">
        <div class="container">
            <h2 class="section-title">Our Mission & Values</h2>
            <p class="section-subtitle">We're guided by core principles that shape everything we build and every decision we make.</p>
            <div class="mission-grid">
                <div class="mission-card animate-fade-in-up">
                    <div class="mission-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation First</h3>
                    <p>We constantly push the boundaries of what's possible, always looking for new ways to solve old problems and create better experiences for our users.</p>
                </div>
                <div class="mission-card animate-fade-in-up">
                    <div class="mission-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Customer Success</h3>
                    <p>Your success is our success. We're committed to understanding your needs and building solutions that genuinely help your business grow.</p>
                </div>
                <div class="mission-card animate-fade-in-up">
                    <div class="mission-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Trust & Security</h3>
                    <p>We take security seriously. Your data is protected with enterprise-grade security measures and we're transparent about how we handle your information.</p>
                </div>
                <div class="mission-card animate-fade-in-up">
                    <div class="mission-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Global Impact</h3>
                    <p>We believe technology should make the world better. Our platform helps businesses of all sizes reduce waste, improve efficiency, and create more value.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>10,000+</h3>
                    <p>Happy Customers</p>
                </div>
                <div class="stat-item">
                    <h3>50+</h3>
                    <p>Countries Served</p>
                </div>
                <div class="stat-item">
                    <h3>99.9%</h3>
                    <p>Uptime Guarantee</p>
                </div>
                <div class="stat-item">
                    <h3>24/7</h3>
                    <p>Customer Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team section">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <p class="section-subtitle">The passionate people behind Parrot Admin who are dedicated to your success.</p>
            <div class="team-grid">
                <div class="team-member animate-fade-in-up">
                    <div class="team-photo">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3>Sarah Johnson</h3>
                        <div class="position">CEO & Founder</div>
                        <p>Former tech executive with 15+ years experience in SaaS and enterprise software. Passionate about building products that make a difference.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member animate-fade-in-up">
                    <div class="team-photo">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3>Michael Chen</h3>
                        <div class="position">CTO</div>
                        <p>Full-stack engineer with expertise in scalable architecture and cloud infrastructure. Leads our technical vision and product development.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member animate-fade-in-up">
                    <div class="team-photo">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3>Emily Rodriguez</h3>
                        <div class="position">Head of Design</div>
                        <p>UX/UI designer focused on creating intuitive, beautiful interfaces that users love. Believes great design should be invisible.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member animate-fade-in-up">
                    <div class="team-photo">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3>David Kim</h3>
                        <div class="position">Head of Customer Success</div>
                        <p>Customer success expert who ensures every client gets maximum value from our platform. Your success is his mission.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Join Our Mission?</h2>
            <p>Be part of the thousands of businesses already transforming their operations with Parrot Admin.</p>
            <a href="/#pricing" class="btn btn-primary">Get Started Today</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Parrot Admin</h3>
                    <p>Transform your business with our modern SaaS solution. Built for growth, designed for success.</p>
                    <div class="footer-social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Product</h3>
                    <p><a href="/#features">Features</a></p>
                    <p><a href="/#pricing">Pricing</a></p>
                    <p><a href="#">API</a></p>
                    <p><a href="#">Documentation</a></p>
                </div>
                <div class="footer-section">
                    <h3>Company</h3>
                    <p><a href="/about">About</a></p>
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

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in-up').forEach(element => {
            observer.observe(element);
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Parrot Admin About Page Loaded');
        });
    </script>
</body>
</html> 