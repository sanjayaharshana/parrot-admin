<style>
    /* Common Styles for Landing Pages */
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

    /* Container utility class */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Button Styles */
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

    /* Section Styles */
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

    /* Features Section */
    .features {
        padding: 5rem 0;
        background: #f8fafc;
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
        background: #f7fafc;
        position: relative;
        overflow: hidden;
    }

    .pricing::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="pricing-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23667eea" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23pricing-pattern)"/></svg>');
        pointer-events: none;
    }

    .pricing .container {
        position: relative;
        z-index: 1;
    }

    .pricing-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 3rem;
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

    .pricing-actions .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }

    .pricing-actions .btn:disabled:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    /* Feature Comparison Table */
    .feature-comparison {
        margin-top: 4rem;
        padding-top: 3rem;
        border-top: 1px solid #e2e8f0;
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

    /* Responsive Design */
    @media (max-width: 768px) {
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

    /* Mobile-specific pricing adjustments */
    @media (max-width: 768px) {
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

<script>
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
        console.log('Landing Page Loaded');
        
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