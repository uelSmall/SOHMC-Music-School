# SOHMC-Music-School — Improvement Backlog

## Bugs / Cleanup
- [ ] `LessonController::index()` builds `$lessonsByInstrument` that the view ignores — dead code, either use or remove
- [ ] Old `default-avatar.jpg` still in `/public/img/` — can delete
- [ ] Unused `isAdmin()` method in `BookedLessonPolicy`

## Architecture
- [ ] Two parallel assignment systems (`lesson_student` pivot vs `lesson_student_assignments` table) — consolidate to one source of truth

## Production
- [ ] Permission changes need to run on Hostinger: `view_assigned_lessons` granted to teacher and administrator roles — run `php artisan db:seed --class=PermissionRoleTableSeeder` or manually assign via tinker

## UX
- [ ] Teacher dashboard and admin dashboard are very similar — differentiate admin view (teacher workload breakdown, revenue stats, student enrollment trends)
- [ ] Lesson library has no "featured" or "new" indicator — students don't know what's worth checking out

## Performance
- [ ] `LessonSearch` eager loads assignments per query — watch as student count grows
