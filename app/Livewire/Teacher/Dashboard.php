<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
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
        ];

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

        return view('teacher.dashboard', [
            'stats' => $stats,
            'progress' => $progress,
            'upcomingAssignments' => $upcomingAssignments,
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}
