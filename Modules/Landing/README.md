# Landing Module - Header & Footer Partials

This module provides reusable header and footer partials for creating consistent landing pages across your Laravel application.

## Structure

```
Modules/Landing/resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php          # Main layout with header/footer
│   │   └── master.blade.php       # Original layout
│   └── partials/
│       ├── header.blade.php       # Header partial with navigation
│       ├── footer.blade.php       # Footer partial with links
│       └── common-styles.blade.php # Shared CSS and JavaScript
├── index.blade.php                # Original landing page
├── index-new.blade.php           # New landing page using partials
└── about.blade.php               # About page using partials
```

## Usage

### Creating a New Landing Page

1. **Extend the layout:**
```php
@extends('landing::components.layouts.app')
```

2. **Define your content:**
```php
@section('content')
    <!-- Your page content here -->
    <section class="hero">
        <div class="hero-content">
            <h1>Your Page Title</h1>
            <p>Your page description</p>
        </div>
    </section>
    
    <!-- More sections... -->
@endsection
```

3. **Add custom styles (optional):**
```php
@push('styles')
<style>
    /* Your custom styles */
</style>
@endpush
```

4. **Add custom scripts (optional):**
```php
@push('scripts')
<script>
    // Your custom JavaScript
</script>
@endpush
```

### Available Components

#### Header Partial (`@include('landing::components.partials.header')`)
- Fixed navigation bar with logo
- Responsive mobile menu
- Smooth scrolling navigation
- Scroll effect that changes header appearance

#### Footer Partial (`@include('landing::components.partials.footer')`)
- Company information
- Product links
- Social media links
- Copyright information with dynamic year

#### Common Styles (`@include('landing::components.partials.common-styles')`)
- Reset CSS
- Typography (Inter font)
- Button styles (primary, secondary)
- Section layouts (hero, features, pricing, CTA)
- Responsive design
- Animations and transitions

### Available CSS Classes

#### Buttons
- `.btn` - Base button style
- `.btn-primary` - Primary button (white background, colored text)
- `.btn-secondary` - Secondary button (transparent with border)

#### Sections
- `.hero` - Hero section with gradient background
- `.features` - Features section with light background
- `.pricing` - Pricing section with white background
- `.cta-section` - Call-to-action section with gradient background

#### Layout
- `.container` - Centered container with max-width
- `.section-title` - Large centered title
- `.section-subtitle` - Centered subtitle text

#### Cards
- `.feature-card` - Feature card with hover effects
- `.pricing-card` - Pricing card with hover effects
- `.pricing-card.featured` - Featured pricing card (scaled up)

### Example: Creating a Contact Page

```php
@extends('landing::components.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Contact Us</h1>
            <p>Get in touch with our team. We're here to help!</p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Us</h3>
                    <p>hello@parrotadmin.com</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Call Us</h3>
                    <p>+1 (555) 123-4567</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Visit Us</h3>
                    <p>123 Business St, Suite 100<br>San Francisco, CA 94105</p>
                </div>
            </div>
        </div>
    </section>
@endsection
```

### Customization

#### Changing Colors
The primary colors used throughout the design are:
- Primary gradient: `#667eea` to `#764ba2`
- Text colors: `#1a202c` (dark), `#718096` (medium), `#a0aec0` (light)
- Background colors: `#f8fafc` (light), `#1a202c` (dark)

#### Modifying Navigation
Edit `Modules/Landing/resources/views/components/partials/header.blade.php` to:
- Change the logo text
- Add/remove navigation items
- Modify the "Get Started" button

#### Updating Footer
Edit `Modules/Landing/resources/views/components/partials/footer.blade.php` to:
- Update company information
- Modify social media links
- Change footer links

### Features

- **Responsive Design**: Works on all device sizes
- **Smooth Animations**: Fade-in effects and hover animations
- **Mobile Menu**: Collapsible navigation for mobile devices
- **SEO Friendly**: Proper meta tags and semantic HTML
- **Accessible**: Keyboard navigation and screen reader support
- **Fast Loading**: Optimized CSS and minimal JavaScript

### Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

This module is part of the Parrot Admin project and follows the same license terms. 