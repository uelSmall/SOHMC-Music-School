# AGENTS.md — Sounds of Harmony Music Centre

Guidance for AI coding agents working in this repo. These rules follow *The
Pragmatic Programmer* (Hunt & Thomas) and the project's hard-won operational
lessons.

## Guardrails (highest priority)

- **Ask before adding features.** Do not implement things the user didn't
  request. If an idea seems obviously useful, propose it, don't build it.
- **Live DB is production.** `php artisan tinker`, `migrate`, and tests hit the
  real Supabase Postgres. Never create/delete rows without saying so. Clean up
  any test rows you create.
- **Check in before irreversible actions.** Soft deletes are fine; hard deletes,
  destructive migrations, force-pushes, and mass updates require explicit
  approval.
- **Tests run against the `testing` schema** (`DB_SCHEMA=testing` in
  `phpunit.xml`). To test against live data, set
  `config(['database.connections.pgsql.search_path' => 'public'])` in the test.

## Engineering behavior (non-negotiable)

- **Follow existing conventions**: Laravel patterns, blade style, module
  structure (`Modules/*`), and Spatie usage already in the codebase.
- **Verify before done**: run `php -l`, `php artisan view:cache`, and a smoke
  test against the live DB for anything that touches data.
- **Be honest about risks and tradeoffs** — blunt, not sugar-coated. If
  something isn't a good idea, say so.
- **Prefer real code over commentary.** Keep the user in the loop on big
  decisions.
- **Keep the engineering daybook**: when you discover a quirk or make a
  decision, note it in the session summary so future sessions inherit it.

## Pragmatic Programmer principles in practice

- **DRY** — reuse helpers instead of copy-pasting. Existing ones:
  - `log_activity($description, $subject, $url, $logName, $causer)` — all
    admin activity feed entries must go through this single helper.
  - `receipt_logo_base64()` — the only way receipts/PDFs get their logo.
  - `titleFromFilename()`, `app_name()`, `setting()` — general utilities.
- **Tracer bullets** — ship thin end-to-end slices, then iterate. No
  big-bang features.
- **Ruthless testing** — verify each layer; never claim "done" on lint alone.
- **Reversibility** — prefer soft deletes; keep changes undoable.
- **Debugging** — read the error, find the root cause, don't guess. The live
  DB often tells the real story.
- **Broken windows** — fix small issues immediately (e.g., dead code, warnings).
- **Good-enough software** — know when to stop; don't gold-plate.
- **Great expectations** — state what could still bite after the win.

## Known project quirks (learned the hard way)

- **Spatie legacy schema**: `model_has_roles` has **no `guard_name` column**.
  `syncRoles()`/`assignRole()` and even Eloquent `save()` can silently fail to
  persist on the live DB. Use **raw `DB::table()` writes** for role/name
  changes. Role ids: 35=super admin, 36=administrator, 37=teacher,
  38=student, 39=parent.
- **Users**: `super@admin.com` = Uel Small (super admin, the owner).
  `admin@admin.com` = Malchiel Small (administrator + teacher, the developer).
- **Admin route group** requires `role:administrator|super admin`. Only a
  super admin may delete another super admin (controller guard + hidden button).
- **Gallery photo uploads** are per-file AJAX (`storePhotoAjax` →
  `admin.gallery.store-photo-ajax`) to dodge PHP request limits; JS calls
  `admin.gallery.log-photos` once per batch for the activity feed. Do not
  revert to one giant multi-file POST.
- **Payments**: `barryvdh/laravel-dompdf` must be installed on Hostinger
  (`composer install` after pull). Receipt emails use a plain-HTML template —
  the `mail::` components 500, don't use them.
- **Media** lives on the local `media` disk → `/storage/media`. `storage:link`
  on Hostinger must exist for uploads/thumbnails to resolve.
- **Frontend gallery** shows only `status = 1` items; no pagination.

## Deploy

- Hostinger auto-deploys the `feature/client-dashboard` branch. The user
  commits and pushes via VS Code — don't commit/push unless asked.
- After composer/lock changes, Hostinger needs `composer install`.