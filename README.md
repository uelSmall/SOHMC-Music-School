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

Build production assets for deployment:

```bash
npm run build
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

## Hostinger / GitHub Deployment

This repository is structured for Hostinger shared hosting with a deployment-safe layout:

- The full Laravel application lives in `laravel-app/`
- The public web entrypoint is served from `public_html/`
- `public_html/index.php` forwards requests to `laravel-app/public/index.php`

Deploy steps:

```bash
composer install
npm install
npm run build
```

Make sure the built Vite assets are committed under `laravel-app/public/build/` and then push to GitHub. Hostinger can deploy the repository directly from GitHub using this structure.

## Seeded Demo Accounts

- `teacher1@example.com` / `password`
- `student1@example.com` / `password`
- `admin@admin.com` / `password`

## Where To Look

- Lesson model & relations: [Modules/Lesson/Models/Lesson.php](Modules/Lesson/Models/Lesson.php)
- Assignment model & logic: [Modules/Lesson/Models/LessonStudentAssignment.php](Modules/Lesson/Models/LessonStudentAssignment.php)
- Backend Livewire components: [app/Livewire/Backend/Lessons](app/Livewire/Backend/Lessons), [app/Livewire/Backend/Assignments](app/Livewire/Backend/Assignments)
- Frontend Livewire components: [app/Livewire/Frontend/Lessons](app/Livewire/Frontend/Lessons)
- Student-facing controller & view: [app/Http/Controllers/LessonController.php](app/Http/Controllers/LessonController.php), [resources/views/lessons/index.blade.php](resources/views/lessons/index.blade.php)
- Seeders: [database/seeders](database/seeders) (RoleSeeder, UserSeeder, LessonSeeder)
- Project docs: [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md)
- Feature guide: [docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md)

## Key Features

✅ **Assignment Tracking** - Track which lessons students are assigned with status (assigned, started, in_progress, completed)  
✅ **Instrument Grouping** - Lessons organized by instrument (piano, guitar, vocals, percussion)  
✅ **Search & Filtering** - Reactive Livewire search and filters for lessons and assignments  
✅ **Progress Tracking** - Color-coded status badges and progress dashboard  
✅ **Teacher Dashboard** - Assign lessons, monitor progress, update status  
✅ **Student Dashboard** - View assigned lessons, update progress, filter by status  
✅ **Modern UX** - TailwindCSS cards, tabs, badges, and modals  

## Key Routes

- **Student Lessons**: `GET /lessons` - Search, filter, and view assigned lessons
- **Teacher Dashboard**: `GET /admin/assignments` - Manage and monitor student assignments
- **Admin Lessons**: `GET /admin/lessons` - Create/edit/publish lessons (Livewire)

## Next Improvements (tracked in TODO)

1. Add `instrument` column + migration and seed sample instruments (piano, guitar, vocals, percussion).
2. Add navigation link to `/lessons` in the main menu.
3. Enhance profile page (extra fields, deletion workflow).

---
This `README.md` mirrors the project summary and is intended for quick onboarding. For more details, open [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md).
