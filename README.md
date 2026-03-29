# Expense Tracker

A full-stack personal finance application for tracking expenses, income, budgets, and categories. Built with a **Vue 3** frontend and **Laravel 13** backend.

## Tech Stack

### Frontend

| Category       | Technology                               |
| -------------- | ---------------------------------------- |
| Framework      | Vue 3.5 + TypeScript 5.9                 |
| Build Tool     | Vite 7.3                                 |
| UI Components  | Reka UI 2.9 (headless)                   |
| Styling        | Tailwind CSS 4.2                         |
| Icons          | Lucide Vue Next                          |
| State          | Pinia 3.0                                |
| Server State   | TanStack Vue Query 5.95                  |
| Forms          | TanStack Vue Form 1.28 + Zod 4.3        |
| HTTP Client    | Axios                                    |
| Utilities      | VueUse 14.2, date-fns 4.1               |
| Notifications  | vue-sonner                               |
| Linting        | ESLint 10 + Oxlint 1.50 + Prettier 3.8  |

### Backend

| Category       | Technology                               |
| -------------- | ---------------------------------------- |
| Framework      | Laravel 13 (PHP 8.3+)                    |
| Database       | PostgreSQL                               |
| Authentication | Laravel Sanctum (token-based)            |
| Testing        | Pest PHP 4.4                             |
| Code Style     | Laravel Pint                             |

## Features

- **Authentication** — Register, login, logout, forgot/reset password
- **Expenses** — Track spending with categories and dates
- **Income** — Record income sources with monthly detail view
- **Budgets** — Set spending limits per category and period
- **Categories** — Custom categories with icons
- **Dashboard** — Overview of financial data

## Project Structure

```
expense-tracker/
├── backend/          # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   ├── Services/
│   │   └── Repositories/
│   ├── database/migrations/
│   ├── routes/api.php
│   └── .env.example
├── frontend/         # Vue SPA
│   ├── src/
│   │   ├── features/     # Feature-based modules
│   │   ├── components/   # Shared UI components
│   │   ├── stores/       # Pinia stores
│   │   └── router/       # Vue Router config
│   └── .env
└── README.md
```

## Prerequisites

- PHP 8.3+
- Composer
- Node.js 20.19+ or 22.12+
- Bun (frontend package manager)
- PostgreSQL

## Getting Started

### Backend

```bash
cd backend

# Install dependencies and set up environment
composer setup
```

This runs: `composer install`, copies `.env.example` to `.env`, generates app key, runs migrations, and installs frontend dependencies.

Alternatively, set up manually:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure your `.env` with PostgreSQL credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=expense-tracker
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

### Frontend

```bash
cd frontend
bun install
```

Create a `.env` file:

```env
VITE_API_URL=http://localhost:8000
```

### Running the App

**Option 1** — Start everything from the backend:

```bash
cd backend
composer dev
```

This concurrently starts the Laravel server, queue worker, log viewer, and Vite dev server.

**Option 2** — Start separately:

```bash
# Terminal 1 - Backend
cd backend
php artisan serve

# Terminal 2 - Frontend
cd frontend
bun run dev
```

## API Endpoints

### Authentication

| Method | Endpoint                  | Description            |
| ------ | ------------------------- | ---------------------- |
| POST   | `/api/auth/register`      | Register new user      |
| POST   | `/api/auth/login`         | Login                  |
| POST   | `/api/auth/logout`        | Logout (auth required) |
| GET    | `/api/auth`               | Get current user       |
| POST   | `/api/auth/forgot-password` | Request password reset |
| POST   | `/api/auth/reset-password`  | Reset password         |

### Resources (all require authentication)

| Resource     | Endpoints                                         |
| ------------ | ------------------------------------------------- |
| Categories   | `GET/POST /api/category`, `PUT/DELETE /api/category/{id}` |
| Budgets      | `GET/POST /api/budget`, `PUT/DELETE /api/budget/{id}`     |
| Income       | `GET/POST /api/income`, `PUT/DELETE /api/income/{id}`, `GET /api/income/detail` |
| Expenses     | `GET/POST /api/expense`, `PUT/DELETE /api/expense/{id}`   |

## Testing

```bash
cd backend
composer test
```

## License

This project is open-sourced software.
