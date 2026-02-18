# Laravel Starter — Project Specific README

This repository is a Laravel 12 starter application customized for lesson management with teacher ownership, student assignment, and a Livewire-powered backend admin UI. The project is modular, uses TailwindCSS for frontend styling, and includes idempotent seeders for demo data and RBAC via Spatie.

For a detailed project summary (keeps evolving), see [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md).

## Quick Start

Install dependencies:

```bash
composer install
npm install
```

Build assets (dev):

```bash
npm run dev
```

Run migrations and seeders:

```bash
php artisan migrate --seed
# or for a clean slate
php artisan migrate:fresh --seed
```

Serve locally:

```bash
php artisan serve
# Visit http://127.0.0.1:8000/lessons (student view) or /admin/lessons (admin)
```

## Seeded Demo Accounts

- `teacher1@example.com` / `password`
- `student1@example.com` / `password`
- `admin@admin.com` / `password`

## Where To Look

- Lesson model & relations: [Modules/Lesson/Models/Lesson.php](Modules/Lesson/Models/Lesson.php)
- Backend Livewire components: [app/Livewire/Backend/Lessons](app/Livewire/Backend/Lessons)
- Student-facing controller & view: [app/Http/Controllers/LessonController.php](app/Http/Controllers/LessonController.php), [resources/views/lessons/index.blade.php](resources/views/lessons/index.blade.php)
- Seeders: [database/seeders](database/seeders) (RoleSeeder, UserSeeder, LessonSeeder)
- Project docs: [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md)

## Next Improvements (tracked in TODO)

1. Add `instrument` column + migration and seed sample instruments (piano, guitar, vocals, percussion).
2. Add navigation link to `/lessons` in the main menu.
3. Enhance profile page (extra fields, deletion workflow).

---
This `README.md` mirrors the project summary and is intended for quick onboarding. For more details, open [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md).
