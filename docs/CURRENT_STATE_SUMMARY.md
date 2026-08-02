# Current State Summary - SOHMC Music School

This is the single source of truth for the current project state on branch `feature/role-based-dashboards`.

## Project Purpose

SOHMC is a modular Laravel-based music school platform focused on:

- lesson management,
- assigning lessons to students,
- tracking student progress,
- role-based backend workflows for teachers/admins.

## Tech Stack

- Laravel 12
- PHP 8.x
- Livewire v3
- Blade + TailwindCSS
- PostgreSQL (Supabase)
- Vite
- Spatie Laravel Permission

## Core Functional Areas

### Lessons

- Lesson CRUD and management for backend users.
- Lesson attributes include status, ordering, publication metadata, and instrument.
- Soft delete support and audit-style fields are part of the model design.

### Assignments

- Teachers/admins can assign lessons to students.
- Assignment flow includes status progression (assigned to completed).
- Optional due dates are supported.
- Assignment dashboards provide role-specific visibility.

### Student Experience

- `/lessons` provides a student-facing view with:
  - search,
  - filters,
  - status-based tabs,
  - progress update actions.

### Teacher/Admin Experience

- `/admin/lessons` for lesson administration.
- `/admin/assignments` for assignment creation and tracking.

## Architecture Notes

- Domain logic is organized under `Modules/`.
- UI behavior is driven by Livewire components in `app/Livewire/`.
- Blade views are split by backend/frontend contexts.
- Seeders are documented as idempotent to reduce duplicate-data issues during repeated setup.

## Database and Environment

- Active DB direction is PostgreSQL via Supabase.
- Local project has Supabase CLI initialized and linked.
- DB runtime settings are read from `.env` (not committed).

## Demo Accounts (Documented)

- student1@example.com / password
- teacher1@example.com / password
- admin@admin.com / password

## Current Known Status

- Project restored from GitHub.
- Supabase connection path configured and working locally.
- Frontend and backend dev servers run correctly (`npm run dev` + `php artisan serve`).
- Documentation exists across multiple files; this document is the consolidated current-state reference.

## Priority Next Step

Primary next focus: full UI redesign/polish pass.

Suggested order:

1. define visual system (typography, colors, spacing, components),
2. redesign high-traffic pages first (`/lessons`, `/admin/assignments`, `/admin/lessons`),
3. propagate style system across remaining pages.

## Reference Documents

- `docs/PROJECT_SUMMARY.md`
- `docs/FEATURES_GUIDE.md`
- `LESSON_MODULE_SUMMARY.md`
- `COMPLETION_SUMMARY.md`
- `DOCS_INDEX.md`