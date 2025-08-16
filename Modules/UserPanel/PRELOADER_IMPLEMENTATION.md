# UserPanel Preloader Implementation

## Overview
The UserPanel now includes a preloader that shows only on the right side (main content area) and not on the sidebar. This provides a better user experience by indicating loading states without blocking the entire interface.

## Features

### ✅ **Right-Side Only Display**
- Preloader covers only the main content area (right side)
- Sidebar remains visible and accessible during loading
- Responsive design that adapts to mobile devices

### ✅ **Automatic Triggers**
- Shows automatically when page loads
- Activates during fetch/AJAX requests
- Shows on page navigation
- Hides automatically after operations complete

### ✅ **Manual Control**
- Global functions to show/hide preloader
- Can be triggered from any JavaScript code
- Smooth fade in/out animations

### ✅ **Smart Positioning**
- Automatically positioned to avoid sidebar
- Uses CSS calc() for precise positioning
- Mobile-responsive fallback

## Implementation Details

### Files Modified/Created
1. **`Modules/UserPanel/resources/views/components/layouts/master.blade.php`**
   - Added preloader HTML structure
   - Included asset compilation

2. **`Modules/UserPanel/resources/assets/sass/app.scss`**
   - Preloader styling with responsive design
   - Dark mode support
   - Smooth animations

3. **`Modules/UserPanel/resources/assets/js/app.js`**
   - Preloader management class
   - Event handling
   - Fetch request interception

4. **`Modules/UserPanel/resources/views/preloader-demo.blade.php`**
   - Demo page for testing functionality

## Usage

### Automatic Usage
The preloader works automatically for:
- Page loads
- Fetch requests
- AJAX requests
- Navigation events

### Manual Control
```javascript
// Show preloader
window.showUserPanelPreloader();

// Hide preloader
window.hideUserPanelPreloader();
```

### Custom Events
```javascript
// Trigger preloader for custom AJAX operations
document.dispatchEvent(new CustomEvent('ajax:start'));
// ... your AJAX code ...
document.dispatchEvent(new CustomEvent('ajax:end'));
```

## Styling Customization

### CSS Variables
The preloader uses the existing color scheme:
- Primary color: `#667eea` (blue)
- Background: `rgba(255, 255, 255, 0.95)`
- Text color: `#667eea`

### Customization Options
You can modify the preloader appearance by editing:
- `Modules/UserPanel/resources/assets/sass/app.scss`
- Colors, sizes, animations, and positioning

## Browser Support
- Modern browsers with CSS Grid and Flexbox support
- Mobile responsive with fallbacks
- Progressive enhancement approach

## Performance Considerations
- Lightweight implementation
- Minimal DOM manipulation
- Efficient event handling
- Automatic cleanup after animations

## Troubleshooting

### Preloader Not Showing
1. Check if assets are compiled: `npm run dev` or `npm run build`
2. Verify JavaScript console for errors
3. Ensure preloader element exists in DOM

### Positioning Issues
1. Check sidebar width (default: 16rem/256px)
2. Verify CSS is loading correctly
3. Test on different screen sizes

### Animation Problems
1. Check CSS transition properties
2. Verify z-index values
3. Ensure no conflicting styles

## Future Enhancements
- [ ] Progress bar support
- [ ] Custom loading messages
- [ ] Multiple preloader types
- [ ] Theme integration
- [ ] Performance metrics

## Support
For issues or questions about the preloader implementation, check:
1. Browser console for JavaScript errors
2. Network tab for asset loading issues
3. CSS inspector for styling conflicts
