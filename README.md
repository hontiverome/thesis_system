# Website Template

A modern web application built with Laravel, Vue.js, and Vite, featuring a responsive design with theme support.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Development Setup](#development-setup)
- [Project Structure](#project-structure)
- [Key Components](#key-components)
- [Theming](#theming)

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

4. Set up environment:
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
   - Vite HMR: http://localhost:3000

3. For production build:
   ```bash
   npm run build
   ```

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
