# Project Summary — Laravel Starter

## Purpose
A modular Laravel 12 starter application focused on teaching/lesson management. It provides a backend admin UI (Livewire) for creating lessons, teacher ownership, student assignment, and a simple student-facing lessons page. The project includes idempotent seeders for demo data and an RBAC system (Spatie) for role-based access.

## Tech Stack
- PHP 8.3
- Laravel 12
- Livewire v3 (backend components)
- Blade + TailwindCSS (frontend)
- PostgreSQL (database)
- spatie/laravel-permission (roles & permissions)
- Vite for asset bundling

## Structure Overview
- `app/` — Models, Livewire components, Controllers, Traits.
- `Modules/` — Domain modules; `Modules/Lesson` contains lesson model, migrations, factories.
- `resources/views/` — Blade views and Livewire blades (`resources/views/livewire/backend/lessons/`).
- `routes/web.php` — Web routes (admin and public routes). Backend admin routes are prefixed with `/admin`.
- `database/seeders/` — Role, User, Lesson seeders (idempotent with `updateOrCreate` / `firstOrCreate`).

## Key Files
- Lesson model: `Modules/Lesson/Models/Lesson.php` — relations: `teacher()` and `students()`.
- Backend Livewire: `app/Livewire/Backend/Lessons/LessonList.php`, `LessonForm.php` and blades in `resources/views/livewire/backend/lessons/`.
- Student-facing lessons: `app/Http/Controllers/LessonController.php` and `resources/views/lessons/index.blade.php` (grouped by instrument).
- Profile compatibility: `app/Http/Controllers/ProfileController.php`, `resources/views/profile/edit.blade.php`.

## Developer Workflows
- Install: `composer install` and `npm install`.
- Assets: `npm run dev` or `composer run dev` for quick dev build.
- Migrate & seed: `php artisan migrate --seed` or `php artisan migrate:fresh --seed`.
- Serve: `php artisan serve` → visit `/admin/lessons` for backend, `/lessons` for student view.

## Seeded Accounts (demo)
- `teacher1@example.com` / `password`
- `student1@example.com` / `password`
- `admin@admin.com` / `password`

## Conventions & Notes
- Modular domains live under `Modules/` to keep features isolated.
- Use Form Request classes for validations when adding controllers.
- Seeders are built to be idempotent to avoid duplicate key errors.
- The `lessons` table currently does not include an `instrument` column; the student view groups by `$lesson->instrument` if present and otherwise displays under "general".

## Pending / Next Improvements
1. Add an `instrument` column (migration) and seed common instruments (piano, guitar, vocals, percussion).
2. Add a navigation link to the main menu for `/lessons`.
3. Enhance profile page (extra fields, proper account deletion flow).

---
This document is intended to be updated as the project evolves. Edit `docs/PROJECT_SUMMARY.md` to add details for new features, API endpoints, or contributor notes.
