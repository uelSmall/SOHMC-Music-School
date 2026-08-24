# SOHMC-Music-School — Improvement Backlog

## Bugs / Cleanup
- [x] `LessonController::index()` builds `$lessonsByInstrument` that the view ignores — dead code, either use or remove
- [ ] Old `default-avatar.jpg` still in `/public/img/` — can delete
- [ ] Unused `isAdmin()` method in `BookedLessonPolicy`

## Architecture
- [x] Two parallel assignment systems (`lesson_student` pivot vs `lesson_student_assignments` table) — consolidated to `lesson_student_assignments` only

## Production
- [ ] Permission changes need to run on Hostinger: `view_assigned_lessons` granted to teacher and administrator roles — run `php artisan db:seed --class=PermissionRoleTableSeeder` or manually assign via tinker

## UX
- [ ] Teacher dashboard and admin dashboard are very similar — differentiate admin view (teacher workload breakdown, revenue stats, student enrollment trends)
- [x] Lesson library has no "featured" or "new" indicator — added "New" badge for lessons ≤14 days old

## Performance
- [ ] `LessonSearch` eager loads assignments per query — watch as student count grows

## Fixed
- [x] Orphaned student status UI — wired up `UpdateStudentAssignmentStatus` on lesson show page
- [x] Re-assigning reset student progress — now preserves existing status
- [x] No student notification on teacher status changes — added notification
- [x] Admin role assignment bug — `roles` array stripped before `$user->update()`
