<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Enums\AssignmentStatus;
use Modules\Lesson\Enums\LessonStatus;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            $lessonsQuery = Lesson::whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        } elseif ($user->hasRole('parent')) {
            $lessonsQuery = Lesson::whereHas('students.parents', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        } elseif ($user->hasRole('teacher')) {
            $lessonsQuery = Lesson::where('teacher_id', $user->id);
        } else {
            // administrator or super admin
            $lessonsQuery = Lesson::query();
        }

        $lessons = $lessonsQuery->orderBy('order')->get();

        // Group by instrument if available, otherwise group under "General"
        $lessonsByInstrument = $lessons->groupBy(function ($lesson) {
            $instrument = null;

            if (isset($lesson->instrument) && $lesson->instrument) {
                $instrument = (string) $lesson->instrument;
            }

            return Str::lower($instrument ?? 'general');
        });

        return view('lessons.index', compact('lessonsByInstrument'));
    }

    public function show(Lesson $lesson)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($this->canAccessLesson($user, $lesson), 403);

        $childrenIds = $user->hasRole('parent')
            ? $user->children()->pluck('users.id')
            : collect([$user->id]);

        $lesson->load([
            'teacher:id,name',
            'assignedStudents' => function ($query) use ($childrenIds) {
                $query->whereIn('student_id', $childrenIds)
                    ->with('student:id,name', 'latestComment.teacher:id,name');
            },
        ]);

        return view('lessons.show', compact('lesson'));
    }

    public function download(Lesson $lesson): Response
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($this->canAccessLesson($user, $lesson), 403);
        abort_unless($lesson->file_path, 404);
        abort_unless(Storage::disk('public')->exists($lesson->file_path), 404);

        $extension = pathinfo($lesson->file_path, PATHINFO_EXTENSION);
        $filename = Str::slug($lesson->title ?: 'lesson-material');
        $downloadName = $extension ? "{$filename}.{$extension}" : $filename;

        return Storage::disk('public')->download($lesson->file_path, $downloadName);
    }

    public function preview(Lesson $lesson)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($this->canAccessLesson($user, $lesson), 403);
        abort_unless($lesson->file_path, 404);
        abort_unless(Storage::disk('public')->exists($lesson->file_path), 404);

        return Storage::disk('public')->response($lesson->file_path);
    }

    public function markAsStarted(Lesson $lesson): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($this->canAccessLesson($user, $lesson), 403);
        abort_unless($user->hasRole('student'), 403);

        $assignment = $lesson->assignedStudents()
            ->where('student_id', $user->id)
            ->first();

        abort_unless($assignment, 404);

        if ($assignment->status === AssignmentStatus::Assigned) {
            $assignment->update(['status' => AssignmentStatus::Started->value]);
        }

        return redirect()
            ->route('lessons.show', $lesson)
            ->with('message', 'Lesson marked as started.');
    }

    private function canAccessLesson(User $user, Lesson $lesson): bool
    {
        if ($user->hasRole('super admin') || $user->hasRole('administrator')) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            return (int) $lesson->teacher_id === (int) $user->id;
        }

        if ($user->hasRole('student')) {
            // Students can view all published lessons (library)
            if ($lesson->status === LessonStatus::Published) {
                return true;
            }

            // Also allow access to assigned lessons regardless of status
            $hasAssignmentRecord = $lesson->assignedStudents()
                ->where('student_id', $user->id)
                ->exists();

            if ($hasAssignmentRecord) {
                return true;
            }

            return $lesson->students()
                ->where('users.id', $user->id)
                ->exists();
        }

        if ($user->hasRole('parent')) {
            // Check assignedStudents (new system)
            $childrenIds = $user->children()->pluck('users.id');
            if ($childrenIds->isNotEmpty() && $lesson->assignedStudents()->whereIn('student_id', $childrenIds)->exists()) {
                return true;
            }

            // Fallback: legacy pivot
            return $lesson->students()
                ->whereHas('parents', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->exists();
        }

        return false;
    }
}
