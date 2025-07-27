<x-auth::layouts.auth 
    heroTitle="Join Parrot Admin" 
    heroSubtitle="Create your account and start transforming your business operations with our powerful SaaS platform."
>
    <div class="auth-form">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join thousands of businesses using Parrot Admin</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input input-with-icon @error('name') error @enderror" 
                        value="{{ old('name') }}" 
                        required 
                        autocomplete="name"
                        placeholder="Enter your full name"
                    >
                </div>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input input-with-icon @error('email') error @enderror" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        placeholder="Enter your email"
                    >
                </div>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input input-with-icon @error('password') error @enderror" 
                        required 
                        autocomplete="new-password"
                        placeholder="Create a strong password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="form-helper">
                    Password must be at least 8 characters long
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input input-with-icon" 
                        required 
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    I agree to the <a href="#" style="color: #667eea;">Terms of Service</a> and <a href="#" style="color: #667eea;">Privacy Policy</a>
                </label>
            </div>

            <div class="form-checkbox">
                <input type="checkbox" id="newsletter" name="newsletter">
                <label for="newsletter">
                    Send me product updates and marketing communications
                </label>
            </div>

            <button type="submit" class="btn btn-primary" id="registerBtn">
                Create Account
            </button>
        </form>

        <div class="auth-divider">
            <span>or sign up with</span>
        </div>

        <div class="social-login">
            <a href="#" class="social-btn">
                <i class="fab fa-google"></i>
                <span>Google</span>
            </a>
            <a href="#" class="social-btn">
                <i class="fab fa-github"></i>
                <span>GitHub</span>
            </a>
        </div>

        <div class="auth-links">
            <span>Already have an account? </span>
            <a href="{{ route('login') }}">Sign in</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;
            const icon = toggle.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const helper = this.parentElement.nextElementSibling;
            
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (password.length < 8) feedback.push('At least 8 characters');
            if (!/[a-z]/.test(password)) feedback.push('Lowercase letter');
            if (!/[A-Z]/.test(password)) feedback.push('Uppercase letter');
            if (!/[0-9]/.test(password)) feedback.push('Number');
            if (!/[^A-Za-z0-9]/.test(password)) feedback.push('Special character');
            
            if (strength >= 4) {
                helper.style.color = '#059669';
                helper.textContent = 'Strong password';
            } else if (strength >= 3) {
                helper.style.color = '#d97706';
                helper.textContent = 'Good password';
            } else if (strength >= 2) {
                helper.style.color = '#dc2626';
                helper.textContent = 'Weak password';
            } else {
                helper.style.color = '#718096';
                helper.textContent = 'Password must be at least 8 characters long';
            }
        });

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (confirmation && password !== confirmation) {
                this.classList.add('error');
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('form-error')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'form-error';
                    errorDiv.textContent = 'Passwords do not match';
                    this.parentElement.parentElement.appendChild(errorDiv);
                }
            } else {
                this.classList.remove('error');
                const errorDiv = this.parentElement.parentElement.querySelector('.form-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });

        // Form submission with loading state
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('registerBtn');
            btn.classList.add('loading');
            btn.textContent = 'Creating Account...';
        });

        // Auto-focus on name field
        document.addEventListener('DOMContentLoaded', function() {
            const nameField = document.getElementById('name');
            if (nameField) {
                nameField.focus();
            }
        });
    </script>
</x-auth::layouts.auth> 