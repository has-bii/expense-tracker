# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

- **Setup**: `composer run setup` (install deps, generate key, migrate, build frontend)
- **Dev server**: `composer run dev` (runs PHP server, queue worker, log tail, and Vite concurrently)
- **Run all tests**: `composer run test` (clears config cache, then runs Pest)
- **Run single test**: `php artisan test --filter=TestName`
- **Lint**: `./vendor/bin/pint` (Laravel Pint - PSR-12 based PHP formatting)
- **Lint check only**: `./vendor/bin/pint --test`
- **Tinker (REPL)**: `php artisan tinker`
- **Run migrations**: `php artisan migrate`

## Architecture

Laravel 13 REST API with Sanctum token authentication. PostgreSQL database (SQLite in-memory for tests).

### Request Flow

`Route → Controller → Service → Repository → Eloquent Model`

- **Controllers** (`app/Http/Controllers/`) — Handle HTTP, validate via Form Requests, delegate to Services
- **Services** (`app/Services/`) — Business logic layer (CategoryService, ExpenseService, IncomeService, BudgetService, DashboardService)
- **Repositories** (`app/Repositories/`) — Data access abstraction over Eloquent (CategoryRepository, ExpenseRepository, IncomeRepository, NotificationRepository)
- **Form Requests** (`app/Http/Requests/`) — Input validation classes for create/update operations
- **Observers** (`app/Observers/`) — Model lifecycle hooks; ExpenseObserver and IncomeObserver clear DashboardService cache on mutations

### Key Patterns

- `ForceJsonResponse` middleware is prepended to all API routes, ensuring JSON responses regardless of Accept header
- Enums (`app/Enums/`) define `Period` (daily/weekly/monthly) and `Order` (asc/desc) for query filtering
- DashboardService uses per-user caching, invalidated by model observers on expense/income changes
- All protected routes use `auth:sanctum` middleware group

### Test Environment

Tests use Pest framework with SQLite `:memory:` database, array cache/session/mail drivers, and sync queue (configured in `phpunit.xml`).
