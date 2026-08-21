<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\LessonRequest;
use Modules\Lesson\Models\Lesson;
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

        return view('admin.dashboard', [
            'schoolStats' => $schoolStats,
            'recentStudents' => $recentStudents,
            'recentActivity' => $recentActivity,
            'notifications' => $notifications,
            'teacherWorkload' => $teacherWorkload,
            'enrollmentTrends' => $enrollmentTrends,
        ])->layout('components.layouts.admin');
    }
}
