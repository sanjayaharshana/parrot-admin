# Auth Module - Authentication System

This module provides a complete authentication system for the Parrot Admin application with modern, responsive design.

## Features

- **User Registration** - Secure account creation with validation
- **User Login** - Email/password authentication with remember me
- **Password Reset** - Forgot password functionality
- **Social Login** - Google and GitHub integration (ready for implementation)
- **Modern UI** - Beautiful, responsive design with animations
- **Form Validation** - Real-time client-side and server-side validation
- **Security** - CSRF protection, password hashing, session management

## Structure

```
Modules/Auth/
├── app/
│   └── Http/
│       └── Controllers/
│           └── AuthController.php      # Authentication logic
├── resources/
│   └── views/
│       ├── components/
│       │   └── layouts/
│       │       ├── auth.blade.php      # Auth layout with styling
│       │       └── master.blade.php    # Original layout
│       ├── login.blade.php             # Login page
│       ├── register.blade.php          # Registration page
│       └── forgot-password.blade.php   # Password reset page
├── routes/
│   └── web.php                         # Authentication routes
└── README.md                           # This file
```

## Routes

### Guest Routes (Not Authenticated)
- `GET /login` - Show login form
- `POST /login` - Handle login submission
- `GET /register` - Show registration form
- `POST /register` - Handle registration submission
- `GET /forgot-password` - Show forgot password form

### Authenticated Routes
- `POST /logout` - Handle user logout

## Usage

### Login Page
```php
// Route to login page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Handle login
Route::post('/login', [AuthController::class, 'login']);
```

**Features:**
- Email and password fields with validation
- Remember me checkbox
- Password visibility toggle
- Social login options (Google, GitHub)
- Forgot password link
- Link to registration page

### Registration Page
```php
// Route to registration page
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Handle registration
Route::post('/register', [AuthController::class, 'register']);
```

**Features:**
- Full name, email, and password fields
- Password strength validation
- Password confirmation
- Terms of service agreement
- Newsletter subscription option
- Social registration options
- Link to login page

### Forgot Password Page
```php
// Route to forgot password page
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
```

**Features:**
- Email field for password reset
- Clear instructions
- Back to login link

## Authentication Controller Methods

### `showLogin()`
Displays the login form with validation errors and session messages.

### `login(Request $request)`
Handles login form submission:
- Validates email and password
- Attempts authentication with remember me option
- Regenerates session for security
- Redirects to intended page or home

### `showRegister()`
Displays the registration form with validation errors.

### `register(Request $request)`
Handles registration form submission:
- Validates all required fields
- Creates new user with hashed password
- Fires Registered event
- Logs in the new user
- Redirects to home page

### `showForgotPassword()`
Displays the forgot password form.

### `logout(Request $request)`
Handles user logout:
- Logs out the user
- Invalidates session
- Regenerates CSRF token
- Redirects to home page

## Form Validation

### Login Validation
```php
$credentials = $request->validate([
    'email' => ['required', 'email'],
    'password' => ['required'],
]);
```

### Registration Validation
```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'terms' => ['required', 'accepted'],
]);
```

## UI Components

### Auth Layout (`auth.blade.php`)
- Modern card-based design
- Gradient background with pattern overlay
- Responsive design for all devices
- Font Awesome icons
- Inter font family

### Form Elements
- **Input Groups** - Icons and password toggles
- **Validation States** - Error highlighting and messages
- **Loading States** - Button loading animations
- **Helper Text** - Password strength indicators

### Styling Classes
- `.auth-container` - Main container with background
- `.auth-content` - White card with shadow
- `.auth-form` - Form styling
- `.form-group` - Form field groups
- `.form-input` - Input field styling
- `.btn-primary` - Primary button with gradient
- `.alert` - Success/error message styling

## Security Features

### CSRF Protection
All forms include CSRF tokens for protection against cross-site request forgery.

### Password Security
- Passwords are hashed using Laravel's Hash facade
- Password strength validation
- Password confirmation requirement

### Session Security
- Session regeneration on login
- Session invalidation on logout
- CSRF token regeneration

### Input Validation
- Server-side validation for all inputs
- Client-side validation for better UX
- XSS protection through Laravel's built-in escaping

## Social Login Integration

The authentication system is prepared for social login integration:

### Google OAuth
```html
<a href="#" class="social-btn">
    <i class="fab fa-google"></i>
    <span>Google</span>
</a>
```

### GitHub OAuth
```html
<a href="#" class="social-btn">
    <i class="fab fa-github"></i>
    <span>GitHub</span>
</a>
```

To implement social login:
1. Install Laravel Socialite
2. Configure OAuth providers
3. Add social login methods to AuthController
4. Update routes for social login callbacks

## Customization

### Changing Colors
The primary colors used in the design:
- Primary gradient: `#667eea` to `#764ba2`
- Text colors: `#1a202c` (dark), `#718096` (medium)
- Background: `#f8fafc` (light)

### Modifying Validation Rules
Edit the validation rules in the AuthController methods to customize requirements.

### Adding New Fields
To add new fields to registration:
1. Add field to registration form
2. Update validation rules
3. Add field to User model fillable array
4. Update database migration if needed

## Error Handling

### Validation Errors
- Displayed below each form field
- Styled with red color and error class
- Server-side validation with fallback

### Session Messages
- Success messages for password reset
- Error messages for failed login attempts
- Styled alerts for user feedback

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Dependencies

- Laravel Framework
- Font Awesome (for icons)
- Inter font (Google Fonts)
- Modern CSS features (Grid, Flexbox, CSS Variables)

## License

This module is part of the Parrot Admin project and follows the same license terms. 