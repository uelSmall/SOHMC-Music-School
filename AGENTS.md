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

## Payments & receipts (decisions, Sep 2026)

- **Receipt numbers never reuse.** `Payment::nextReceiptNumber()` reads the
  Postgres sequence (`payments_id_seq`), *not* a row count — a hard delete
  would otherwise reissue a number that may already have been emailed out.
  Gaps after deletes are correct.
- **Student numbers** live on `users.student_number`, format
  `SOHMC-<year>-NNNN` (year = join year). Assigned on student registration
  and admin user creation; `assignStudentNumber()` is also called lazily in
  `PaymentController@store` so a receipt always shows one. Never reused
  (`withTrashed()` when computing the next number). All existing students
  were backfilled.
- **Payment delete is a hard delete with a confirm gate** in the UI. There is
  no undo; `PaymentController@destroy` also removes the payment's activity
  rows so the dashboard never shows a dead "View" link.
- **WhatsApp**: `Payment::whatsappShareUrl()` builds a `wa.me` link with the
  public receipt URL prefilled; it prefills the student's `mobile` when set,
  otherwise opens WhatsApp to pick a contact. `wa.me` sends text only — the
  PDF is reached via the public receipt link, not attached.
- **Public receipt URL** is `frontend.receipts.view` (token-based, no auth).
  Note the route name lives inside the `frontend.` name group.
- **Phone** is `users.mobile` (not `phone`). Not collected at sign-up — users
  add it from their profile; admins can set it on create/edit.

## Receipt template

- Styles live in `receipts/receipt-content.blade.php`, **scoped under
  `.receipt-doc`**, so the same CSS serves both the dompdf PDF page
  (`receipts/receipt.blade.php`) and the admin preview (embedded in
  `admin/payments/show.blade.php`). Never put receipt styles in `body` — the
  preview shares the admin page.
- A `@media screen and (max-width: 600px)` block stacks the header for
  phones. dompdf lays out at ~744px, so those rules never affect the PDF.
- The receipt logo comes from `receipt_logo_base64()` — the single source.

## Registration & email verification

Flow: `/register` (guest, gated by `USER_REGISTRATION`) → Livewire
`Auth\Register` → user created + role assigned + student number → `Registered`
event sends the framework `VerifyEmail` notification (signed link) → logged in
but `verified` middleware holds them at `verification.notice` until they click.

Gotchas learned the hard way:

- **Verification link expiry** is `config('auth.verification.expire')`, set to
  **120 minutes** (Laravel's 60 was too short). The signature is tied to the
  full URL, so **`APP_URL` must exactly match the live domain** (scheme +
  www/non-www) or every link 403s.
- **Expired/invalid links are handled gracefully**: `bootstrap/app.php`
  catches `InvalidSignatureException` for the `verification.verify` route and
  redirects to `verification.notice` with status `verification-link-invalid`.
  That is why the verify route must keep its name.
- **Verification requires being logged in** (route is behind `auth`). Opening
  the email on a different device/browser lands on `/login` first — expected.
- **Soft-deleted emails can't re-register**: `users_email_unique` is a *full*
  unique index, so a trashed row still owns its email. `Auth\Register` now
  detects that and shows "deactivated account" guidance instead of the opaque
  "already taken." A real fix (partial unique index + `whereNull('deleted_at')`
  on the unique rule) needs a production migration and sign-off.
- **Mail config is environment, not code.** Local `.env` is `MAIL_MAILER=log`
  (nothing is sent — written to the log) and `APP_URL=http://localhost`. On
  Hostinger verify `MAIL_MAILER=smtp`, a from-address on the real domain with
  SPF/DKIM, and matching `APP_URL`. The deploy guide's `smtp.mailtrap.io` is a
  *capture* service — real mail will never leave it.

## Deploy

- Hostinger auto-deploys the `feature/client-dashboard` branch. The user
  commits and pushes via VS Code — don't commit/push unless asked.
- After composer/lock changes, Hostinger needs `composer install`.