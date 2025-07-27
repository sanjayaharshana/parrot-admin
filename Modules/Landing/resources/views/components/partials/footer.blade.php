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
            <p>&copy; {{ date('Y') }} Parrot Admin. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>
    </div>
</footer>

<style>
    /* Footer Styles */
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

    /* Container utility class */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .social-links {
            justify-content: center;
        }
    }
</style> 