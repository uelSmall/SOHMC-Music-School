<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Models\LessonRequest;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Models\LessonStudentAssignment;

class Dashboard extends Component
{
    public function markNotificationAsRead(string $notificationId): void
    {
        $notification = auth()->user()->unreadNotifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notify', message: 'Notification dismissed.', type: 'success');
        }
    }

    public function markAllNotificationsAsRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->dispatch('notify', message: 'All notifications marked as read.', type: 'success');
    }

    public function render()
    {
        $teacherId = auth()->id();
        $today = now()->toDateString();

        $lessonsQuery = Lesson::query()->where('teacher_id', $teacherId);

        $assignmentsQuery = LessonStudentAssignment::query()
            ->whereHas('lesson', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            });

        $stats = [
            'lessons_total' => (clone $lessonsQuery)->count(),
            'lessons_published' => (clone $lessonsQuery)->where('status', 'published')->count(),
            'assignments_total' => (clone $assignmentsQuery)->count(),
            'assignments_due_soon' => (clone $assignmentsQuery)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '>=', now()->toDateString())
                ->whereDate('due_date', '<=', now()->addDays(7)->toDateString())
                ->count(),
            'lesson_requests_pending' => LessonRequest::query()
                ->where('teacher_id', $teacherId)
                ->where('status', LessonRequestStatus::Pending->value)
                ->count(),
        ];

        $bookedLessonsQuery = BookedLesson::query()
            ->where('teacher_id', $teacherId)
            ->with(['student:id,name', 'instrument:id,name', 'lessonRequest:id,student_note,teacher_note']);

        $lessonManagementStats = [
            'today' => (clone $bookedLessonsQuery)->whereDate('lesson_date', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'upcoming' => (clone $bookedLessonsQuery)->whereDate('lesson_date', '>', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'completed' => (clone $bookedLessonsQuery)->where('status', LessonStatus::Completed->value)->count(),
            'cancelled' => (clone $bookedLessonsQuery)->where('status', LessonStatus::Cancelled->value)->count(),
        ];

        $bookedLessons = (clone $bookedLessonsQuery)
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get();

        $todaysLessons = $bookedLessons->filter(function (BookedLesson $lesson) use ($today): bool {
            return $lesson->status === LessonStatus::Scheduled && $lesson->lesson_date?->toDateString() === $today;
        })->values();

        $upcomingLessons = $bookedLessons->filter(function (BookedLesson $lesson) use ($today): bool {
            return $lesson->status === LessonStatus::Scheduled && $lesson->lesson_date?->toDateString() > $today;
        })->values();

        $completedLessons = $bookedLessons->where('status', LessonStatus::Completed)->take(5)->values();
        $cancelledLessons = $bookedLessons->where('status', LessonStatus::Cancelled)->take(5)->values();

        $progress = [
            'assigned' => (clone $assignmentsQuery)->where('status', 'assigned')->count(),
            'started' => (clone $assignmentsQuery)->where('status', 'started')->count(),
            'in_progress' => (clone $assignmentsQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $assignmentsQuery)->where('status', 'completed')->count(),
        ];

        $upcomingAssignments = (clone $assignmentsQuery)
            ->with(['lesson:id,title', 'student:id,name'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', now()->toDateString())
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        $notifications = auth()->user()->unreadNotifications()->latest()->limit(5)->get();

        $pendingLessonRequests = LessonRequest::query()
            ->where('teacher_id', $teacherId)
            ->where('status', LessonRequestStatus::Pending)
            ->with(['student:id,name', 'instrument:id,name'])
            ->orderBy('requested_date')
            ->orderBy('requested_start_time')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', [
            'stats' => $stats,
            'progress' => $progress,
            'upcomingAssignments' => $upcomingAssignments,
            'notifications' => $notifications,
            'pendingLessonRequests' => $pendingLessonRequests,
            'lessonManagementStats' => $lessonManagementStats,
            'todaysLessons' => $todaysLessons,
            'upcomingLessons' => $upcomingLessons,
            'completedLessons' => $completedLessons,
            'cancelledLessons' => $cancelledLessons,
        ])->layout('layouts.app');
    }
}
