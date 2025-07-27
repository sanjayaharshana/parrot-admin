<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $title ?? 'Authentication' }} - {{ config('app.name', 'Parrot Admin') }}</title>

    <meta name="description" content="{{ $description ?? 'Secure authentication for Parrot Admin' }}">
    <meta name="keywords" content="{{ $keywords ?? 'login, signup, authentication, security' }}">
    <meta name="author" content="{{ $author ?? 'Parrot Admin' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Vite CSS --}}
    {{-- {{ module_vite('build-auth', 'resources/assets/sass/app.scss') }} --}}

    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Left Side - Content -->
        <div class="auth-content-side">
            <div class="auth-content-overlay"></div>
            <div class="auth-content-inner">
                <div class="auth-brand">
                    <i class="fas fa-parrot"></i>
                    <span>Parrot Admin</span>
                </div>
                
                <div class="auth-hero">
                    <h1 class="auth-hero-title">{{ $heroTitle ?? 'Transform Your Business' }}</h1>
                    <p class="auth-hero-subtitle">{{ $heroSubtitle ?? 'Join thousands of businesses already using Parrot Admin to streamline operations and boost productivity.' }}</p>
                </div>

                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Lightning Fast</h3>
                            <p>Built with modern technologies for optimal performance</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Enterprise Security</h3>
                            <p>Bank-level security with encryption and compliance</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Advanced Analytics</h3>
                            <p>Get deep insights into your business performance</p>
                        </div>
                    </div>
                </div>

                <div class="auth-testimonial">
                    <div class="auth-testimonial-content">
                        <p>"Parrot Admin has completely transformed how we manage our operations. The interface is intuitive and the features are exactly what we needed."</p>
                        <div class="auth-testimonial-author">
                            <div class="auth-testimonial-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="auth-testimonial-info">
                                <h4>Sarah Johnson</h4>
                                <span>CEO, TechCorp</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="auth-form-side">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <a href="/" class="auth-logo-mobile">
                        <i class="fas fa-parrot"></i>
                        <span>Parrot Admin</span>
                    </a>
                </div>
                
                <div class="auth-form-main">
                    {{ $slot }}
                </div>
                
                <div class="auth-form-footer">
                    <p>&copy; {{ date('Y') }} Parrot Admin. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

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
            background: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            position: relative;
        }

        /* Left Side - Content */
        .auth-content-side {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            overflow: hidden;
        }

        .auth-content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .auth-content-inner {
            position: relative;
            z-index: 1;
            color: white;
            max-width: 500px;
            width: 100%;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 3rem;
        }

        .auth-brand i {
            font-size: 2.5rem;
        }

        .auth-hero {
            margin-bottom: 3rem;
        }

        .auth-hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .auth-hero-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .auth-features {
            margin-bottom: 3rem;
        }

        .auth-feature {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .auth-feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .auth-feature-content h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .auth-feature-content p {
            font-size: 0.875rem;
            opacity: 0.8;
            line-height: 1.5;
        }

        .auth-testimonial {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .auth-testimonial-content p {
            font-style: italic;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .auth-testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .auth-testimonial-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .auth-testimonial-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
        }

        .auth-testimonial-info span {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Right Side - Form */
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
        }

        .auth-form-container {
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .auth-form-header {
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        .auth-logo-mobile {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #667eea;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .auth-logo-mobile i {
            font-size: 1.75rem;
        }

        .auth-form-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-form-footer {
            padding: 1rem 0;
            text-align: center;
            color: #718096;
            font-size: 0.875rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 2rem;
        }

        /* Form Styles */
        .auth-form {
            width: 100%;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .auth-subtitle {
            color: #718096;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: #e53e3e;
        }

        .form-error {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-helper {
            color: #718096;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 10;
        }

        .input-with-icon {
            padding-left: 2.75rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-checkbox input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #667eea;
        }

        .form-checkbox label {
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #374151;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .auth-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .auth-divider span {
            background: white;
            padding: 0 1rem;
            color: #718096;
            font-size: 0.875rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            color: #374151;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        /* Loading State */
        .btn.loading {
            position: relative;
            color: transparent;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        /* Success/Error Messages */
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-info {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-content-side {
                padding: 2rem;
                min-height: 40vh;
            }

            .auth-content-inner {
                max-width: 100%;
            }

            .auth-hero-title {
                font-size: 2rem;
            }

            .auth-features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }

            .auth-form-side {
                min-height: 60vh;
                padding: 1.5rem;
            }

            .auth-form-container {
                min-height: auto;
            }
        }

        @media (max-width: 768px) {
            .auth-content-side {
                padding: 1.5rem;
                min-height: 35vh;
            }

            .auth-hero-title {
                font-size: 1.75rem;
            }

            .auth-hero-subtitle {
                font-size: 1rem;
            }

            .auth-features {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .auth-feature {
                margin-bottom: 1rem;
            }

            .auth-form-side {
                padding: 1rem;
                min-height: 65vh;
            }

            .auth-form-header,
            .auth-form-main,
            .auth-form-footer {
                padding: 1rem 0;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .social-login {
                flex-direction: column;
            }

            .auth-testimonial {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .auth-content-side {
                min-height: 30vh;
                padding: 1rem;
            }

            .auth-hero-title {
                font-size: 1.5rem;
            }

            .auth-form-side {
                min-height: 70vh;
                padding: 0.75rem;
            }

            .auth-brand {
                font-size: 1.5rem;
            }

            .auth-brand i {
                font-size: 2rem;
            }
        }
    </style>

    @stack('scripts')
</body>
</html> 