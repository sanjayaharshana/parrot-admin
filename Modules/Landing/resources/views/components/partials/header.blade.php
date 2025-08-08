<!-- Header -->
<header class="header" id="header">
    <nav class="nav-container">
        <a href="#" class="logo">Parrot Admin</a>
        <ul class="nav-menu">
            <li><a href="#features">Features</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="{{ route('documentation.index') }}" class="nav-link {{ request()->routeIs('documentation.*') ? 'active' : '' }}">Documentation</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            @if(request()->routeIs('documentation.*'))
                <li><a href="{{ route('documentation.create') }}" class="btn-get-started">
                    <i class="fas fa-plus mr-2"></i>
                    Add Page
                </a></li>
            @endif
            @guest
                <li><a href="/login" class="btn-get-started">Get Started</a></li>
            @else
                <li class="profile-dropdown">
                    <div class="profile-trigger" id="profile-trigger">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="profile-menu" id="profile-menu">
                        <a href="{{ url('dashboard') }}" class="profile-item">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="#" class="profile-item">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <div class="profile-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="profile-form">
                            @csrf
                            <button type="submit" class="profile-item profile-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </li>
            @endguest
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

    .nav-menu a.active {
        color: #667eea;
        font-weight: 600;
    }

    .header.scrolled .nav-menu a.active {
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

    /* Profile Dropdown Styles */
    .profile-dropdown {
        position: relative;
    }

    .profile-trigger {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .profile-trigger:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .profile-trigger .user-name {
        font-weight: 600;
    }

    .profile-trigger i {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
    }

    .profile-trigger.active i {
        transform: rotate(180deg);
    }

    .profile-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
        margin-top: 0.5rem;
    }

    .profile-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        display: block;
    }

    .profile-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #333 !important;
        text-decoration: none;
        transition: background-color 0.3s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .profile-item:hover {
        background-color: #f8f9fa;
        color: #667eea !important;
    }

    .profile-item i {
        width: 16px;
        text-align: center;
    }

    .profile-divider {
        height: 1px;
        background-color: #e9ecef;
        margin: 0.5rem 0;
    }

    .profile-logout {
        color: #dc3545 !important;
    }

    .profile-logout:hover {
        background-color: #f8d7da;
        color: #dc3545 !important;
    }

    .profile-form {
        margin: 0;
    }

    /* Header scrolled state for profile dropdown */
    .header.scrolled .profile-trigger {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .header.scrolled .profile-trigger:hover {
        background: #5a67d8;
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

        .profile-dropdown {
            width: 100%;
            margin-top: 1rem;
        }

        .profile-trigger {
            width: 100%;
            justify-content: center;
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .profile-menu {
            position: static;
            opacity: 1;
            visibility: visible;
            transform: none;
            box-shadow: none;
            background: transparent;
            margin-top: 0.5rem;
            display: none;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-item {
            color: #333;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .profile-item:last-child {
            border-bottom: none;
        }

        .profile-divider {
            display: none;
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

    // Profile dropdown functionality
    function initProfileDropdown() {
        const profileTrigger = document.getElementById('profile-trigger');
        const profileMenu = document.getElementById('profile-menu');

        console.log('Profile trigger:', profileTrigger);
        console.log('Profile menu:', profileMenu);

        if (profileTrigger && profileMenu) {
            profileTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Profile trigger clicked');
                profileTrigger.classList.toggle('active');
                profileMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileTrigger.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileTrigger.classList.remove('active');
                    profileMenu.classList.remove('show');
                }
            });

            // Close dropdown when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    profileTrigger.classList.remove('active');
                    profileMenu.classList.remove('show');
                }
            });
        }
    }

    // Initialize profile dropdown when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfileDropdown);
    } else {
        initProfileDropdown();
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
    })};
</script>
