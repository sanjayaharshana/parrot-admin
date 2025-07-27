<x-auth::layouts.auth 
    heroTitle="Reset Your Password" 
    heroSubtitle="Don't worry, we'll help you get back into your account quickly and securely."
>
    <div class="auth-form">
        <h1 class="auth-title">Forgot Password</h1>
        <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
            @csrf
            
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
                        placeholder="Enter your email address"
                    >
                </div>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="form-helper">
                    We'll send you a password reset link to this email address
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="resetBtn">
                Send Reset Link
            </button>
        </form>

        <div class="auth-links">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left"></i>
                Back to Sign In
            </a>
        </div>
    </div>

    <script>
        // Form submission with loading state
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('resetBtn');
            btn.classList.add('loading');
            btn.textContent = 'Sending...';
        });

        // Auto-focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.focus();
            }
        });
    </script>
</x-auth::layouts.auth> 