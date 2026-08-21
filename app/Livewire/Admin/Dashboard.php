<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\LessonRequest;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Models\LessonStudentAssignment;
use Spatie\Activitylog\Models\Activity;

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
        $userId = auth()->id();
        $today = now()->toDateString();
        $weekEnd = now()->addDays(7)->toDateString();

        // ── School-wide stats ──
        $allLessonsQuery = BookedLesson::query()
            ->with(['student:id,name', 'teacher:id,name', 'instrument:id,name']);

        $schoolStats = [
            'total_students' => User::whereHas('roles', fn ($q) => $q->where('name', 'student'))->count(),
            'total_teachers' => User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->count(),
            'total_lessons' => (clone $allLessonsQuery)->count(),
            'lessons_this_week' => (clone $allLessonsQuery)
                ->where('status', LessonStatus::Scheduled->value)
                ->whereBetween('lesson_date', [$today, $weekEnd])
                ->count(),
            'pending_requests_all' => LessonRequest::query()
                ->where('status', LessonRequestStatus::Pending->value)
                ->count(),
        ];

        $schoolLessonStats = [
            'today' => (clone $allLessonsQuery)->whereDate('lesson_date', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'upcoming' => (clone $allLessonsQuery)->whereDate('lesson_date', '>', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'completed' => (clone $allLessonsQuery)->where('status', LessonStatus::Completed->value)->count(),
            'cancelled' => (clone $allLessonsQuery)->where('status', LessonStatus::Cancelled->value)->count(),
        ];

        $todaysLessonsAll = (clone $allLessonsQuery)
            ->whereDate('lesson_date', $today)
            ->where('status', LessonStatus::Scheduled->value)
            ->orderBy('lesson_start_time')
            ->get();

        $upcomingLessonsAll = (clone $allLessonsQuery)
            ->whereDate('lesson_date', '>', $today)
            ->where('status', LessonStatus::Scheduled->value)
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->limit(8)
            ->get();

        // ── My teacher data ──
        $myLessonsQuery = Lesson::query()->where('teacher_id', $userId);

        $myAssignmentsQuery = LessonStudentAssignment::query()
            ->whereHas('lesson', fn ($q) => $q->where('teacher_id', $userId));

        $myStats = [
            'lessons_total' => (clone $myLessonsQuery)->count(),
            'lessons_published' => (clone $myLessonsQuery)->where('status', 'published')->count(),
            'assignments_total' => (clone $myAssignmentsQuery)->count(),
            'assignments_due_soon' => (clone $myAssignmentsQuery)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '>=', $today)
                ->whereDate('due_date', '<=', $weekEnd)
                ->count(),
            'lesson_requests_pending' => LessonRequest::query()
                ->where('teacher_id', $userId)
                ->where('status', LessonRequestStatus::Pending->value)
                ->count(),
        ];

        $myBookedQuery = BookedLesson::query()
            ->where('teacher_id', $userId)
            ->with(['student:id,name', 'instrument:id,name', 'lessonRequest:id,student_note,teacher_note']);

        $myLessonStats = [
            'today' => (clone $myBookedQuery)->whereDate('lesson_date', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'upcoming' => (clone $myBookedQuery)->whereDate('lesson_date', '>', $today)->where('status', LessonStatus::Scheduled->value)->count(),
            'completed' => (clone $myBookedQuery)->where('status', LessonStatus::Completed->value)->count(),
            'cancelled' => (clone $myBookedQuery)->where('status', LessonStatus::Cancelled->value)->count(),
        ];

        $myBookedLessons = (clone $myBookedQuery)
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get();

        $myTodaysLessons = $myBookedLessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled && $l->lesson_date?->toDateString() === $today)->values();
        $myUpcomingLessons = $myBookedLessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled && $l->lesson_date?->toDateString() > $today)->values();
        $myCompletedLessons = $myBookedLessons->where('status', LessonStatus::Completed)->take(5)->values();
        $myCancelledLessons = $myBookedLessons->where('status', LessonStatus::Cancelled)->take(5)->values();

        $myProgress = [
            'assigned' => (clone $myAssignmentsQuery)->where('status', 'assigned')->count(),
            'started' => (clone $myAssignmentsQuery)->where('status', 'started')->count(),
            'in_progress' => (clone $myAssignmentsQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $myAssignmentsQuery)->where('status', 'completed')->count(),
        ];

        $myUpcomingAssignments = (clone $myAssignmentsQuery)
            ->with(['lesson:id,title', 'student:id,name'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', $today)
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        $myPendingRequests = LessonRequest::query()
            ->where('teacher_id', $userId)
            ->where('status', LessonRequestStatus::Pending->value)
            ->with(['student:id,name', 'instrument:id,name'])
            ->orderBy('requested_date')
            ->orderBy('requested_start_time')
            ->limit(5)
            ->get();

        // ── Shared data ──
        $recentStudents = User::whereHas('roles', fn ($q) => $q->where('name', 'student'))
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at']);

        $recentActivity = Activity::with('causer', 'subject')
            ->latest()
            ->limit(8)
            ->get();

        $notifications = auth()->user()->unreadNotifications()->latest()->limit(5)->get();

        // ── Admin-only: Teacher workload ──
        $teachers = User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))
            ->withCount([
                'bookedLessonsAsTeacher as booked_lessons_count',
            ])
            ->get(['id', 'name', 'email']);

        $teacherWorkload = $teachers->map(function ($teacher) use ($today) {
            $bookedQuery = BookedLesson::where('teacher_id', $teacher->id);

            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'booked_total' => $teacher->booked_lessons_count,
                'booked_completed' => (clone $bookedQuery)->where('status', LessonStatus::Completed->value)->count(),
                'booked_upcoming' => (clone $bookedQuery)->where('status', LessonStatus::Scheduled->value)->where('lesson_date', '>=', $today)->count(),
                'content_count' => Lesson::where('teacher_id', $teacher->id)->count(),
            ];
        });

        // ── Admin-only: Student enrollment trends (last 6 months) ──
        $enrollmentTrends = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = User::whereHas('roles', fn ($q) => $q->where('name', 'student'))
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $enrollmentTrends->push([
                'month' => $month->format('M'),
                'count' => $count,
            ]);
        }

        return view('admin.dashboard', [
            'schoolStats' => $schoolStats,
            'schoolLessonStats' => $schoolLessonStats,
            'todaysLessonsAll' => $todaysLessonsAll,
            'upcomingLessonsAll' => $upcomingLessonsAll,
            'myStats' => $myStats,
            'myLessonStats' => $myLessonStats,
            'myTodaysLessons' => $myTodaysLessons,
            'myUpcomingLessons' => $myUpcomingLessons,
            'myCompletedLessons' => $myCompletedLessons,
            'myCancelledLessons' => $myCancelledLessons,
            'myProgress' => $myProgress,
            'myUpcomingAssignments' => $myUpcomingAssignments,
            'myPendingRequests' => $myPendingRequests,
            'recentStudents' => $recentStudents,
            'recentActivity' => $recentActivity,
            'notifications' => $notifications,
            'teacherWorkload' => $teacherWorkload,
            'enrollmentTrends' => $enrollmentTrends,
        ])->layout('components.layouts.admin');
    }
}
