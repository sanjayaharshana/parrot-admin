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

<style>
    /* Header Styles */
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
    }
</style>

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

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('show');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('show');
            });
        });
    }

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
</script> 