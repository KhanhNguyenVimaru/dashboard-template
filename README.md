# IoT Dashboard Template

This repository is a Laravel 12 + Vue starter focused on fast IoT dashboard prototyping. It ships a RESTful backend (CRUD, search, pagination) plus a modern Vue interface with reusable modals, databoxes, and Chart.js cards so you can monitor devices, sensors, and alerts in real time.

## Highlights
- CRUD + filtering for devices, sensors, and logs exposed through well-structured API routes.
- Clean controller/service/model separation with queue-ready scaffolding and future MQTT hooks.
- Dashboard-first UI with responsive layout, shared modal system, toast notifications, and live charts.
- Tailwind CSS, Vite, and Vue 3 components that stay lightweight yet highly reusable.
- Infrastructure ready for background workers, log tailing, and multi-tenant expansions.

## Tech Stack
- **Backend**: Laravel 12, PHP 8.2, MySQL/PostgreSQL (configurable through `.env`).
- **Frontend**: Vue 3, Vite, Tailwind CSS, Chart.js (dedicated `web/` workspace).
- **Tooling**: Composer 2, Node.js ≥ 20, npm, Laravel Sail/Pail for local orchestration.

## Installation
1. **Backend setup**
   ```bash
   cp .env.example .env           # or customize DB vars manually
   composer install
   php artisan key:generate
   php artisan migrate
   php artisan db:seed            # optional sample data
   npm install                    # for Laravel's asset pipeline if needed
   ```
2. **Frontend (Vue) workspace**
   ```bash
    cd web
    npm install
   ```
3. **Run services**
   ```bash
   # Terminal A - Laravel API
   php artisan serve

   # Terminal B - Laravel asset watcher (optional)
   npm run dev

   # Terminal C - Vue dashboard (standalone)
   cd web && npm run dev
   ```

Visit `http://127.0.0.1:8000` for backend/API and the Vite port (default `5173`) for the IoT dashboard SPA. For a combined local environment with queue workers/logs and Vite, run `composer run dev` which spawns all processes concurrently.
