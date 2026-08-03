<?php

namespace App\Livewire\Parents;

use Livewire\Component;
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
        $parent = auth()->user();

        $children = $parent->children()
            ->with(['assignedLessons.lesson:id,title,instrument,teacher_id,global_note', 'assignedLessons.lesson.teacher:id,name', 'assignedLessons.latestComment.teacher:id,name'])
            ->get();

        $childrenIds = $children->pluck('id');

        $assignmentsQuery = LessonStudentAssignment::query()
            ->whereIn('student_id', $childrenIds);

        $stats = [
            'children_total' => $children->count(),
            'assignments_total' => (clone $assignmentsQuery)->count(),
            'in_progress' => (clone $assignmentsQuery)->whereIn('status', ['started', 'in_progress'])->count(),
            'completed' => (clone $assignmentsQuery)->where('status', 'completed')->count(),
            'due_soon' => (clone $assignmentsQuery)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '>=', now()->toDateString())
                ->whereDate('due_date', '<=', now()->addDays(7)->toDateString())
                ->count(),
        ];

        $childrenSummaries = $children->map(function ($child) {
            $assignments = $child->assignedLessons->sortBy(function ($assignment) {
                return optional($assignment->due_date)->timestamp ?? PHP_INT_MAX;
            })->values();

            $completed = $assignments->where('status', 'completed')->count();
            $inProgress = $assignments->whereIn('status', ['started', 'in_progress'])->count();
            $dueSoon = $assignments->filter(function ($assignment) {
                return $assignment->due_date
                    && $assignment->due_date->between(now()->startOfDay(), now()->addDays(7)->endOfDay());
            })->count();
            $nextAssignment = $assignments
                ->filter(function ($assignment) {
                    return in_array($assignment->status->value, ['assigned', 'started', 'in_progress'], true);
                })
                ->first();

            return [
                'child' => $child,
                'assignments_total' => $assignments->count(),
                'in_progress' => $inProgress,
                'completed' => $completed,
                'due_soon' => $dueSoon,
                'nextAssignment' => $nextAssignment,
                'progress' => $assignments->count() > 0 ? (int) round(($completed / $assignments->count()) * 100) : 0,
            ];
        });

        $upcomingAssignments = (clone $assignmentsQuery)
            ->with(['lesson:id,title,instrument,teacher_id,global_note', 'lesson.teacher:id,name', 'student:id,name', 'latestComment.teacher:id,name'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', now()->toDateString())
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        $notifications = auth()->user()->unreadNotifications()->latest()->limit(5)->get();

        return view('parents.dashboard', [
            'stats' => $stats,
            'childrenSummaries' => $childrenSummaries,
            'upcomingAssignments' => $upcomingAssignments,
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}
