<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Modules\Lesson\Models\LessonStudentAssignment;

class Dashboard extends Component
{
    public function render()
    {
        $studentId = auth()->id();

        $assignmentsQuery = LessonStudentAssignment::query()
            ->where('student_id', $studentId);

        $stats = [
            'assigned_total' => (clone $assignmentsQuery)->count(),
            'assigned' => (clone $assignmentsQuery)->where('status', 'assigned')->count(),
            'in_progress' => (clone $assignmentsQuery)->whereIn('status', ['started', 'in_progress'])->count(),
            'completed' => (clone $assignmentsQuery)->where('status', 'completed')->count(),
            'due_soon' => (clone $assignmentsQuery)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '>=', now()->toDateString())
                ->whereDate('due_date', '<=', now()->addDays(7)->toDateString())
                ->count(),
        ];

        $upcomingAssignments = (clone $assignmentsQuery)
            ->with(['lesson:id,title,instrument', 'lesson.teacher:id,name'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', now()->toDateString())
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        return view('student.dashboard', [
            'stats' => $stats,
            'upcomingAssignments' => $upcomingAssignments,
        ])->layout('layouts.app');
    }
}
