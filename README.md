# Theming and UI Framework

A modern web application built with Laravel, Vue.js, and Vite, featuring a responsive design with theme support.

## Installation

1. **Clone the repository**
   ```bash
   git clone [http://devhub.dost.gov.ph/jaohontiveros/website_template.git]
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

5. **Run database migrations (for future database integrations)**
   ```bash
   php artisan migrate
   ```
6. **Build assets**

   For production:
   ```bash
   npm run build
   ```
   For development:
      ```bash
      npm run dev
      ```

7. **Start the development server**
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

## Project Structure

- `app/` - Laravel application code
- `resources/` - Frontend assets and Vue components
  - `js/` - Vue components and application logic
  - `css/` - Stylesheets
- `routes/` - Application routes
- `config/` - Configuration files
- `database/` - Database migrations and seeders

## Files of Interest:
 - Most of the development will be made within the resources folder and its js and css folders.
 - Inside resources/js/stores contains the theme.js which is the main storage for the themes.

