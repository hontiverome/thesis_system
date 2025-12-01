# Website Template

A modern web application built with Laravel, Vue.js, and Vite, featuring a responsive design with theme support.

## Table of Contents
- [Installation](#installation)
- [Development Setup](#development-setup)
- [Collaboration & Git Workflow](#collaboration--git-workflow)
- [Project Structure](#project-structure)
- [Customizing Views](#customizing-views)
- [Key Components](#key-components)

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm 9+
- MySQL Server or XAMPP
- (make sure to edit the .env example file with correct credentials)

## Installation

1. Clone the repository:
   ```bash
   git clone [https://github.com/hontiverome/thesis_system.git](https://github.com/hontiverome/thesis_system.git)
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

6. Setup Test Account (Requires a setup MySql Server):
   ```bash
   php create_test_user.php
   ```

## Development Setup

1. Start the development server:
   ```bash
   # Start PHP development server
   php artisan serve

   # (If not working test this):
   php -S 127.0.0.1:8000 -t public
   
   # In a separate terminal, start Vite
   npm run dev
   ```

2. Access the application:
   - Development: http://127.0.0.1:8000

## Collaboration & Git Workflow

We follow a strict workflow to separate Frontend (FE) and Backend (BE) development and prevent conflicts.

### 1. Branching Strategy
**Never push directly to `main` or `dev`.** Always create a new branch for your task.

**Branch Naming Convention:**
Format: `type/stack/task-description`

| Type | Stack | Description | **Example** |
| :--- | :--- | :--- | :--- |
| `feat` | `/fe/` | Frontend UI/Vue work | `feat/fe/login-form` |
| `feat` | `/be/` | Backend API/Laravel work | `feat/be/user-auth-api` |
| `fix` | `/fe/` or `/be/` | Bug fixes | `fix/fe/navbar-alignment` |
| `docs` | `/gen/` | Documentation changes | `docs/gen/update-readme` |

### 2. How to Branch (Step-by-Step Tutorial)
Follow these exact commands every time you start a new task to ensure you are branching correctly.

**Step 1: Get the latest code**
Before creating your branch, switch to `dev` and pull the latest changes so you aren't working on old code.
```bash
git checkout dev
git pull origin dev
```

**Step 2: Create your feature branch**
Create and switch to your new branch using the naming convention.
```bash
# Syntax: git checkout -b [branch-name]
git checkout -b feat/fe/login-page
# Switching Branches
git switch feat/fe/login-page
```

**Step 3: Work and Commit**
Make your changes, then stage and commit them.
```bash
git add .
git commit -m "form: added username and password inputs"
```

**Step 4: Push to GitHub**
Push your specific branch to the cloud.
```bash
git push -u origin feat/fe/login-page
```
*After pushing, go to GitHub to open the Pull Request.*

### 3. Commit Messages
Commits must be small, descriptive, and follow the **Context: Action** format.

**Format:** `context: description`

* **Context:** A one-word tag describing what you touched (e.g., `form`, `auth`, `api`, `db`, `style`).
* **Description:** Short summary in lowercase.

**Examples:**
* ✅ `form: added username input field`
* ✅ `api: updated login response json`
* ✅ `db: seeded test admin user`
* ✅ `style: fixed padding on mobile view`
* ❌ `fixed stuff` (Too vague)
* ❌ `Update Login.vue` (No context)

### 4. Pull Requests (PR)
When your feature is ready:
1.  **Push** your branch to GitHub.
2.  Open a Pull Request targeting the **`dev`** branch (NOT `main`).
3.  **PR Description Requirements:**
    * **Frontend:** Must include a screenshot of the UI change.
    * **Backend:** Must include a sample of the JSON response or Postman screenshot.
4.  Request a review from at least one team member.
5.  Once approved, merge into `dev`.

---

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
- `register_view.vue` - Register page
- `dashboard_view.vue` - Dashboard page
- `settings_view.vue` - Settings page

These views are completely customizable and can be:
- Modified to fit your content
- Removed if not needed
- Used as references for creating new views

To modify a view, simply edit the corresponding `.vue` file. To remove a view, delete the file and update the router configuration in `resources/js/router/index.js` to remove the associated route.

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
- Do not forget to add the new route buttons to navbar, sidebar, and mobile_bottom_navbar. They can be added near the comment named "Navigation Items" inside all three components.

### Views
- Located in `resources/js/views/`
- Each view is a Vue component
- Use composition API for better code organization

### State Management
- Uses Pinia for state management
- Stores are in `resources/js/stores/`
- Theme configuration in `theme.js`

## Theming

The application supports dynamic theming:
- Theme configuration: `resources/js/stores/theme.js`
- Global styles: `resources/css/app.css`
- Toggle theme in the application settings

## Mobile Support

- Responsive design for all screen sizes
- Mobile-optimized navigation
- Touch-friendly interface
