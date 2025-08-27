# Website Template

A modern web application built with Laravel, Vue.js, and Vite, featuring a responsive design with theme support.

## Table of Contents
- [Installation](#installation)
- [Development Setup](#development-setup)
- [Project Structure](#project-structure)
- [Customizing Views](#customizing-views)
- [Key Components](#key-components)

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm 9+

## Installation

1. Clone the repository:
   ```bash
   git clone http://devhub.dost.gov.ph/jaohontiveros/website_template.git
   cd website_template
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Build Scripts:
   ```bash
   npm run build
   ```

5. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

## Development Setup

1. Start the development server:
   ```bash
   # Start PHP development server
   php -S 127.0.0.1:8000 -t public
   
   # In a separate terminal, start Vite
   npm run dev
   ```

2. Access the application:
   - Development: http://127.0.0.1:8000


## Project Structure

```
website_template/
├── app/                # Laravel application code
├── resources/          # Frontend assets
│   ├── js/             # Vue components and logic
│   │   ├── components/ # Reusable components
│   │   ├── router/     # Vue Router configuration
│   │   ├── stores/     # State management (Pinia)
│   │   └── views/      # Page components
│   └── css/            # Global styles
├── routes/             # Application routes
├── config/             # Configuration files
└── database/           # Migrations and seeders
```


## Customizing Views

The template includes several pre-built views in `resources/js/views/` that serve as starting points:
- `home_view.vue` - Main landing page
- `help_view.vue` - Help/Support page

These views are completely customizable and can be:
- Modified to fit your content
- Removed if not needed
- Used as references for creating new views

To modify a view, simply edit the corresponding `.vue` file. To remove a view, delete the file and update the router configuration in `resources/js/router/index.js` to remove the associated route.

### View Styling
Each view's styles are organized in `resources/css/app.css` with clear section comments for easy navigation. Styles are categorized by view name for better maintainability:

```css
/* Home View Styles */
.home-view { ... }

/* Help View Styles */
.help-view { ... }
```

### Modifying Views
1. To modify a view, simply edit the corresponding `.vue` file
2. Update or remove the associated styles in `app.css` if needed
3. The styles are scoped to each view using view-specific class names

### Removing Views
1. Delete the view file from `resources/js/views/`
2. Remove the associated route from `resources/js/router/index.js`
3. Clean up any view-specific styles in `app.css` (look for the view's style section)


## Key Components

### Routing
- Routes are defined in `resources/js/router/index.js`
- Add new routes by extending the `routes` array

### Views
- Located in `resources/js/views/`
- Each view is a Vue component
- Use composition API for better code organization

### State Management
- Uses Pinia for state management
- Stores are in `resources/js/stores/`
- Theme configuration in `theme.js`

## Theming

The application supports dynamic theming with light/dark mode:
- Theme configuration: `resources/js/stores/theme.js`
- Global styles: `resources/css/app.css`
- Toggle theme in the application settings

## Mobile Support

- Responsive design for all screen sizes
- Mobile-optimized navigation
- Touch-friendly interface
