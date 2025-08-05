# Theming and UI Framework

A modern web application built with Laravel, Vue.js, and Vite, featuring a responsive design with theme support.

## Features

- Responsive layout with collapsible sidebar
- Theme management system
- Modern UI components
- Built with Laravel 10 and Vue 3
- Vite for asset bundling

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm 9+
- MySQL 5.7+ or MariaDB 10.3+

## Installation

1. **Clone the repository**
   ```bash
   git clone [your-repository-url]
   cd project
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database**
   Update the `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   For development:
   ```bash
   npm run dev
   ```
   
   For production:
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## Development

- Run Vite dev server:
  ```bash
  npm run dev
  ```

- Build for production:
  ```bash
  npm run build
  ```

## Project Structure

- `app/` - Laravel application code
- `resources/` - Frontend assets and Vue components
  - `js/` - Vue components and application logic
  - `css/` - Stylesheets
- `routes/` - Application routes
- `config/` - Configuration files
- `database/` - Database migrations and seeders

## License

This project is proprietary software. All rights reserved.

## Author

Jerome Andrei O. Hontiveros  
Department of Science and Technology — Project LODI  
Copyright © 2025
