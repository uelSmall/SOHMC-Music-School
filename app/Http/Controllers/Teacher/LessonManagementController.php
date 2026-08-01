<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\CancelLessonRequest;
use App\Http\Requests\Teacher\CompleteLessonRequest;
use App\Http\Requests\Teacher\RescheduleBookedLessonRequest;
use App\Support\Notifications\BookedLessonNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;

class LessonManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BookedLesson::class);

        $teacherId = (int) $request->user()->id;
        $today = now()->toDateString();

        $lessonsQuery = BookedLesson::query()
            ->where('teacher_id', $teacherId)
            ->with([
                'student:id,name',
                'instrument:id,name',
                'lessonRequest:id,student_note,teacher_note',
            ]);

        $statistics = [
            'today' => (clone $lessonsQuery)->whereDate('lesson_date', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'upcoming' => (clone $lessonsQuery)->whereDate('lesson_date', '>', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'completed' => (clone $lessonsQuery)->where('status', LessonStatus::Completed->value)->count(),
            'cancelled' => (clone $lessonsQuery)->where('status', LessonStatus::Cancelled->value)->count(),
        ];

        $lessons = (clone $lessonsQuery)
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get();

        $todaysLessons = $lessons->filter(function (BookedLesson $lesson) use ($today): bool {
            return $lesson->status === LessonStatus::Scheduled && $lesson->lesson_date?->toDateString() === $today;
        })->values();

        $upcomingLessons = $lessons->filter(function (BookedLesson $lesson) use ($today): bool {
            return $lesson->status === LessonStatus::Scheduled && $lesson->lesson_date?->toDateString() > $today;
        })->values();

        $completedLessons = $lessons->where('status', LessonStatus::Completed)->values();
        $cancelledLessons = $lessons->where('status', LessonStatus::Cancelled)->values();

        return view('teacher.lesson-management.index', compact(
            'statistics',
            'todaysLessons',
            'upcomingLessons',
            'completedLessons',
            'cancelledLessons'
        ));
    }

    public function show(BookedLesson $lesson): View
    {
        $this->authorize('view', $lesson);

        $lesson->load([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lessonRequest:id,student_note,teacher_note',
        ]);

        return view('teacher.lesson-management.show', compact('lesson'));
    }

    public function complete(CompleteLessonRequest $request, BookedLesson $lesson): RedirectResponse
    {
        $this->authorize('complete', $lesson);

        $lesson->update([
            'status' => LessonStatus::Completed,
            'completed_at' => now(),
        ]);

        return redirect()->route('teacher.lesson-management.show', $lesson)->with('notify', [
            'message' => 'Lesson marked as completed.',
            'type' => 'success',
        ]);
    }

    public function cancel(CancelLessonRequest $request, BookedLesson $lesson, BookedLessonNotificationService $notifications): RedirectResponse
    {
        $this->authorize('cancel', $lesson);

        $lesson->update([
            'status' => LessonStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => $request->string('cancellation_reason')->toString() ?: null,
        ]);

        $notifications->notifyStudentLessonCancelled($lesson);

        return redirect()->route('teacher.lesson-management.show', $lesson)->with('notify', [
            'message' => 'Lesson cancelled successfully.',
            'type' => 'success',
        ]);
    }

    public function reschedule(RescheduleBookedLessonRequest $request, BookedLesson $lesson, BookedLessonNotificationService $notifications): RedirectResponse
    {
        $this->authorize('reschedule', $lesson);

        $newDate = $request->date('new_date');
        $newStartTime = $request->string('new_start_time')->toString();
        $newEndTime = $request->string('new_end_time')->toString();

        if (
            $lesson->lesson_date?->toDateString() === $newDate?->toDateString()
            && $lesson->lesson_start_time === $newStartTime
            && $lesson->lesson_end_time === $newEndTime
        ) {
            return redirect()->route('teacher.lesson-management.show', $lesson)->with('notify', [
                'message' => 'Lesson already uses that schedule.',
                'type' => 'info',
            ]);
        }

        $lesson->update([
            'lesson_date' => $newDate,
            'lesson_start_time' => $newStartTime,
            'lesson_end_time' => $newEndTime,
            'rescheduled_at' => now(),
        ]);

        $notifications->notifyStudentLessonRescheduled($lesson);
        $notifications->notifyTeacherLessonRescheduled($lesson);

        return redirect()->route('teacher.lesson-management.show', $lesson)->with('notify', [
            'message' => 'Lesson rescheduled successfully.',
            'type' => 'success',
        ]);
    }
}