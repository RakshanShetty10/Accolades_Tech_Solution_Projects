# Under Construction Page Templates

A collection of 4 beautiful, responsive under construction page designs that can be added to any website with a single HTML tag. Perfect for websites under development or maintenance.

## Quick Start

Add this to your HTML:

```html
<!-- Include the template -->
<script src="https://raw.githubusercontent.com/YOUR_USERNAME/under-construction-templates/main/js/under-construction.js"></script>

<!-- Add the design -->
<div data-uc-design="minimalist"></div>

<!-- Initialize -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('[data-uc-design]');
        initUnderConstruction(container);
    });
</script>
```

## Available Designs

Choose any of these designs by changing the `data-uc-design` value:

1. **Modern Minimalist** (`minimalist`)
   ```html
   <div data-uc-design="minimalist"></div>
   ```
   - Clean and simple design
   - Animated progress bar
   - Lightweight and elegant

2. **Creative Animated** (`animated`)
   ```html
   <div data-uc-design="animated"></div>
   ```
   - Gradient background
   - Floating animated circles
   - Modern typography

3. **Professional Business** (`business`)
   ```html
   <div data-uc-design="business"></div>
   ```
   - Professional color scheme
   - Countdown timer
   - Clean business layout

4. **Playful Interactive** (`playful`)
   ```html
   <div data-uc-design="playful"></div>
   ```
   - Interactive construction icon
   - Animated loading dots
   - Playful color scheme

## Installation

### Option 1: Direct GitHub Link
```html
<script src="https://raw.githubusercontent.com/YOUR_USERNAME/under-construction-templates/main/js/under-construction.js"></script>
```

### Option 2: Download and Host Locally
1. Download the `js/under-construction.js` file
2. Place it in your project
3. Include it in your HTML:
```html
<script src="path/to/under-construction.js"></script>
```

## Customization

Each design is self-contained with its own CSS. You can customize the colors, fonts, and other properties by modifying the CSS within each design function in the JavaScript file.

### Example Customization
```javascript
// In your HTML file
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('[data-uc-design]');
        // Customize text
        container.setAttribute('data-title', 'My Custom Title');
        container.setAttribute('data-subtitle', 'My Custom Subtitle');
        initUnderConstruction(container);
    });
</script>
```

## Features

- 🎨 4 unique designs
- 📱 Fully responsive
- 🚀 No external dependencies
- ⚡ Lightweight and fast
- 🎯 Easy to implement
- 🔧 Easy to customize

## Browser Support

Works in all modern browsers:
- Chrome
- Firefox
- Safari
- Edge
- Opera

## Contributing

Feel free to contribute to this project by:
1. Forking the repository
2. Creating your feature branch
3. Committing your changes
4. Pushing to the branch
5. Creating a Pull Request

## License

MIT License - Feel free to use in your projects!

## Author

[Rakshan Shetty] - [Your GitHub Profile]

## Support

If you find this project helpful, please give it a ⭐️ on GitHub! 